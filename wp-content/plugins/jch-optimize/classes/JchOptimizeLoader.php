<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/wordpress-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

use JchOptimize\Core\Admin\Tasks;
use JchOptimize\Core\Helper;
use JchOptimize\Core\Logger;
use JchOptimize\Core\Optimize;
use JchOptimize\Core\PageCache;
use JchOptimize\Platform\Cache;
use JchOptimize\Platform\Plugin;
use JchOptimize\Platform\Settings;
use JchOptimize\Platform\Uri;
use JchOptimize\Platform\Utility;


abstract class JchOptimizeLoader
{
	/**
	 * @var Settings|null
	 */
	private static $oParams;

	public static function preboot_init()
	{
		if ( false !== ( $aSettings = get_option( 'jch_options' ) ) )
		{
			update_option( 'jch-optimize_settings', $aSettings );
			delete_option( 'jch_options' );
		}
	}

	public static function init()
	{
		self::$oParams = Plugin::getPluginParams();
		self::runActivationRoutines();

		if ( is_admin() )
		{
			//require_once( JCH_PLUGIN_DIR . 'admin/admin.php' );
			require_once 'JchOptimizeAdmin.php';
			add_action( 'admin_menu', [ 'JchOptimizeAdmin', 'addAdminMenu' ] );
			add_action( 'admin_init', [ 'JchOptimizeAdmin', 'registerOptions' ] );
			add_filter( 'plugin_action_links', [ 'JchOptimizeAdmin', 'loadActionLinks' ], 10, 2 );
		}
		else
		{
			$url_exclude = self::$oParams->get( 'url_exclude', [] );
			$jch_backend = Utility::get( 'jchbackend' );

			if ( defined( 'WP_USE_THEMES' )
			     && WP_USE_THEMES
			     && $jch_backend != 1
			     && version_compare( PHP_VERSION, '5.3.0', '>=' )
			     && ! defined( 'DOING_AJAX' )
			     && ! defined( 'DOING_CRON' )
			     && ! defined( 'APP_REQUEST' )
			     && ! defined( 'XMLRPC_REQUEST' )
			     && ( ! defined( 'SHORTINIT' ) || ( defined( 'SHORTINIT' ) && ! SHORTINIT ) )
			     && ! Helper::findExcludes( $url_exclude, Uri::getInstance()->toString() ) )
			{
				//Disable NextGen Resource Manager; incompatible with plugin
				//add_filter( 'run_ngg_resource_manager', '__return_false' );
				add_action( 'init', [ __CLASS__, 'initializeCache' ], 0 );

				ob_start( [ __CLASS__, 'runOptimize' ] );
			}
		}

		add_action( 'plugins_loaded', [ __CLASS__, 'loadPluginTextDomain' ] );
		register_uninstall_hook( JCH_PLUGIN_FILE, [ 'JchOptimizeLoader', 'runUninstallRoutines' ] );

		if ( self::$oParams->get( 'order_plugin', '0' ) )
		{
			add_action( 'activated_plugin', [ __CLASS__, 'orderPlugin' ] );
			add_action( 'deactivated_plugin', [ __CLASS__, 'orderPlugin' ] );
		}

		if ( self::$oParams->get( 'lazyload_enable', '0' ) )
		{
			add_action( 'wp_head', [ __CLASS__, 'enqueueLazyLoad' ] );
		}

		if ( JCH_PRO )
		{
			self::loadProUpdater();

			if ( self::$oParams->get( 'pro_cache_platform', '0' ) )
			{
				add_filter( 'jch_optimize_get_page_cache_id', [
					__CLASS__,
					'getPageCacheHash'
				],
					10,
					2
				);
			}
		}

		//Ajax functions
		add_action( 'wp_ajax_filetree', [ 'JchOptimizeAdmin', 'doAjaxFileTree' ] );
		add_action( 'wp_ajax_multiselect', [ 'JchOptimizeAdmin', 'doAjaxMultiSelect' ] );
		add_action( 'wp_ajax_optimizeimages', [ 'JchOptimizeAdmin', 'doAjaxOptimizeImages' ] );
		add_action( 'wp_ajax_smartcombine', [ 'JchOptimizeAdmin', 'doAjaxSmartCombine' ] );

		//Helper functions for encoding urls
		function base64_encode_url( $string )
		{
			return strtr( base64_encode( $string ), '+/=', '._-' );
		}

		function base64_decode_url( $string )
		{
			return base64_decode( strtr( $string, '._-', '+/=' ) );
		}
	}

	protected static function runActivationRoutines()
	{
		//Handles activation routines
		include_once 'JchOptimizeInstaller.php';
		$JchOptimizeInstaller = new JchOptimizeInstaller();
		register_activation_hook( JCH_PLUGIN_FILE, [ $JchOptimizeInstaller, 'activate' ] );

		if ( ! file_exists( JCH_PLUGIN_FILE . '/dir.php' ) )
		{
			$JchOptimizeInstaller->activate();
		}

	}

	protected static function loadProUpdater()
	{
		include_once 'JchOptimizeUpdater.php';

		new JchOptimizeUpdater( self::$oParams->get( 'pro_downloadid', '' ) );
	}

	public static function initializeCache()
	{
		PageCache::initialize();
	}

	public static function runUninstallRoutines()
	{
		delete_option( 'jch-optimize_settings' );

		try
		{
			Cache::deleteCache();
		}
		catch ( \JchOptimize\Core\Exception $e )
		{
		}

		Tasks::cleanHtaccess();
	}

	public static function runOptimize( $sHtml )
	{
		if ( ! Helper::validateHtml( $sHtml ) )
		{
			return $sHtml;
		}

		//need to check this here, it could be set dynamically
		global $jch_no_optimize;

		$disable_logged_in = self::$oParams->get( 'disable_logged_in_users', '0' );

		//Need to call Utility::isGuest after init has been called
		if ( $jch_no_optimize || ( $disable_logged_in && ! Utility::isGuest() ) )
		{
			return $sHtml;
		}

		try
		{
			$sOptimizedHtml = Optimize::optimize( self::$oParams, $sHtml );
			Pagecache::store( $sOptimizedHtml );
		}
		catch ( Exception $e )
		{
			Logger::log( $e->getMessage(), self::$oParams );

			$sOptimizedHtml = $sHtml;
		}

		return $sOptimizedHtml;
	}

	public static function loadPluginTextDomain()
	{
		load_plugin_textdomain( 'jch-optimize', false, basename( dirname( JCH_PLUGIN_FILE ) ) . '/languages' );
	}

	public static function orderPlugin()
	{
		$active_plugins = (array) get_option( 'active_plugins', [] );
		$order          = [
			'wp-rocket/wp-rocket.php',
			'wp-super-cache/wp-cache.php',
			'w2-total-cache/w3-total-cache.php',
			'litespeed-cache/litespeed-cache.php',
			'wp-fastest-cache/wpFastestCache.php',
			'wp-optimize/wp-optimize.php',
			'comet-cache/comet-cache.php',
			'hyper-cache/plugin.php',
			'swift-performance/performance.php',
			'jch-optimize/jch-optimize.php'
		];

		//Get the plugins in $order that are currently activated
		$order_short_list = array_intersect( $order, $active_plugins );
		//Remove plugins in $order_short_list from list of activated plugins
		$active_plugins_slist = array_diff( $active_plugins, $order_short_list );
		//Merge $order with $active_plugins_list
		$ordered_active_plugins = array_merge( $order_short_list, $active_plugins_slist );

		update_option( 'active_plugins', $ordered_active_plugins );

		return true;
	}

	public static function enqueueLazyLoad()
	{
		wp_register_script( 'jch-lazyloader-js', JCH_PLUGIN_URL . 'media/core/js/ls.loader.js', [], JCH_VERSION );
		wp_enqueue_script( 'jch-lazyloader-js' );

		if ( JCH_PRO && self::$oParams->get( 'pro_lazyload_effects', '0' ) )
		{
			wp_enqueue_style( 'jch-lazyload-css', JCH_PLUGIN_URL . 'media/core/css/ls.effects.css', [], JCH_VERSION );

			wp_register_script( 'jch-lseffects-js', JCH_PLUGIN_URL . 'media/core/js/ls.loader.effects.js', [ 'jch-lazyloader-js' ], JCH_VERSION );
			wp_enqueue_script( 'jch-lseffects-js' );
		}

		if ( JCH_PRO && ( self::$oParams->get( 'pro_lazyload_bgimages', '0' ) || self::$oParams->get( 'pro_lazyload_audiovideo', '0' ) ) )
		{
			wp_register_script( 'jch-unveilhooks-js', JCH_PLUGIN_URL . 'media/lazysizes/ls.unveilhooks.js', [ 'jch-lazyloader-js' ], JCH_VERSION );
			wp_enqueue_script( 'jch-unveilhooks-js' );
		}

		wp_register_script( 'jch-lazyload-js', JCH_PLUGIN_URL . 'media/lazysizes/lazysizes.js', [ 'jch-lazyloader-js' ], JCH_VERSION );
		wp_enqueue_script( 'jch-lazyload-js' );
	}

	public static function getPageCacheHash( $parts )
	{
		if ( wp_is_mobile() )
		{
			$parts[] = '_MOBILE_';
		}

		return $parts;
	}
}