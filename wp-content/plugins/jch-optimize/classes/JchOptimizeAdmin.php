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

use Awf\Application\Application;
use JchOptimize\Core\Admin\Ajax\Ajax;
use JchOptimize\Helper\Html;
use JchOptimize\Admin\Container;
use JchOptimize\Helper\TabSettings;
use JchOptimize\Platform\Plugin;

abstract class JchOptimizeAdmin
{

	public static function addAdminMenu()
	{
		$menuTitle   = JCH_PRO ? 'JCH Optimize Pro' : 'JCH Optimize';
		$hook_suffix = add_options_page( __( 'JCH Optimize Settings', 'jch-optimize' ), $menuTitle, 'manage_options', 'jch_optimize', [
			__CLASS__,
			'loadAdminPage'
		] );

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'loadResourceFiles' ] );
		add_action( 'admin_head-' . $hook_suffix, [ __CLASS__, 'addScriptsToHead' ] );
		add_action( 'load-' . $hook_suffix, [ __CLASS__, 'initializeSettings' ] );

		add_action( 'admin_bar_menu', [ __CLASS__, 'addMenuToAdminBar' ], 100 );
		add_action( 'admin_init', [ __CLASS__, 'checkMessages' ] );
	}

	public static function loadAdminPage()
	{
		try
		{
			$oContainer   = new Container();
			$oApplication = Application::getInstance( 'JchOptimize', $oContainer );
			$oApplication->initialise();
			$oApplication->route();
			$oApplication->dispatch();
			$oApplication->render();
		}
		catch ( \Exception $e )
		{
			$class = get_class( $e );
			echo <<<HTML
<h1>Application Error</h1>
<p>Please submit the following error message and trace in a support request:</p>
<div class="alert alert-danger">  {$class} &mdash;  {$e->getMessage()}  </div>
<pre class="well"> {$e->getTraceAsString()} </pre>
HTML;

		}
	}

	public static function registerOptions()
	{
		register_setting( 'jchOptimizeOptionsPage', 'jch-optimize_settings', [ 'type' => 'array' ] );
	}

	public static function loadResourceFiles( $hook )
	{
		if ( 'settings_page_jch_optimize' != $hook )
		{
			return;
		}

		wp_enqueue_style( 'jch-bootstrap-css' );
		wp_enqueue_style( 'jch-verticaltabs-css' );
		wp_enqueue_style( 'jch-admin-css' );
		wp_enqueue_style( 'jch-fonts-css' );
		wp_enqueue_style( 'jch-chosen-css' );
		wp_enqueue_style( 'jch-wordpress-css' );
		wp_enqueue_style( 'jch-filetree-css' );

		wp_enqueue_script( 'jch-platformwordpress-js' );
		wp_enqueue_script( 'jch-bootstrap-js' );
		wp_enqueue_script( 'jch-adminutility-js' );
		wp_enqueue_script( 'jch-multiselect-js' );
		wp_enqueue_script( 'jch-smartcombine-js' );

		wp_enqueue_script( 'jch-chosen-js' );
		wp_enqueue_script( 'jch-collapsible-js' );
		wp_enqueue_script( 'jch-filetree-js' );

		if ( JCH_PRO )
		{
			wp_enqueue_style( 'jch-progressbar-css' );
			wp_enqueue_script( 'jquery-ui-progressbar' );
			wp_enqueue_script( 'jch-optimizeimage-js' );
		}
	}

	public static function addScriptsToHead()
	{

		echo <<<HTML
		<style>
                    .chosen-container-multi .chosen-choices li.search-field input[type=text] {
                        height: 25px;
                    }

                    .chosen-container {
                        margin-right: 4px;
                    }

		</style>
		<script type="text/javascript">
		(function($){
                    function submitJchSettings() {
                        $("form.jch-settings-form").submit();
                    }

                    $(document).ready(function () {
                        $(".chzn-custom-value").chosen({
                             disable_search_threshold: 10,
                             width: "300px",
                             placeholder_text_multiple: "Select some options or add items to select"
                         });

                        $('.hasPopover').popover({
                            container: 'body',
                            placement: 'bottom',
                            trigger: 'hover focus',
                            html: true
                        })
                    });
                })(jQuery);
                </script>
                    
HTML;
		if ( JCH_PRO )
		{
			$optimizeImageUrl = add_query_arg( [
				'page' => 'jch_optimize',
				'view' => 'optimizeimage'
			],
				admin_url( 'options-general.php' )
			);

			$aParams    = Plugin::getPluginParams()->toArray();
			$aApiParams = [
				'pro_downloadid'      => '',
				'hidden_api_secret'   => '11e603aa',
				'ignore_optimized'    => '1',
				'recursive'           => '1',
				'pro_api_resize_mode' => '1',
				'pro_next_gen_images' => '1'
			];


			$jch_message = __( 'Please open a directory to optimize images.', 'jch-optimize' );
			$jch_noproid = __( 'Please enter your Download ID on the Configurations tab.', 'jch-optimize' );
			$jch_params  = json_encode( array_intersect_key( $aParams, $aApiParams ) );
			echo <<<HTML

		<script>
                    var jch_message = '{$jch_message}';
                    var jch_noproid = '{$jch_noproid}';
                    var jch_params = JSON.parse('{$jch_params}');
		</script>
HTML;
		}
	}

	public static function initializeSettings()
	{
		//Css files
		wp_register_style( 'jch-bootstrap-css', JCH_PLUGIN_URL . 'media/bootstrap/css/bootstrap.css', [], JCH_VERSION );

		wp_register_style( 'jch-admin-css', JCH_PLUGIN_URL . 'media/core/css/admin.css', [], JCH_VERSION );
		wp_register_style( 'jch-fonts-css', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css' );
		wp_register_style( 'jch-chosen-css', JCH_PLUGIN_URL . 'media/chosen-js/chosen.css', [], JCH_VERSION );
		wp_register_style( 'jch-wordpress-css', JCH_PLUGIN_URL . 'media/css/wordpress.css', [], JCH_VERSION );
		wp_register_style( 'jch-filetree-css', JCH_PLUGIN_URL . 'media/filetree/jquery.filetree.css', [], JCH_VERSION );

		//Javascript files
		wp_register_script( 'jch-bootstrap-js', JCH_PLUGIN_URL . 'media/bootstrap/js/bootstrap.bundle.min.js', [ 'jquery' ], JCH_VERSION, true );
		wp_register_script( 'jch-platformwordpress-js', JCH_PLUGIN_URL . 'media/js/platform-wordpress.js', [ 'jquery' ], JCH_VERSION, true );
		wp_register_script( 'jch-adminutility-js', JCH_PLUGIN_URL . 'media/core/js/admin-utility.js', [ 'jquery' ], JCH_VERSION, true );
		wp_register_script( 'jch-multiselect-js', JCH_PLUGIN_URL . 'media/core/js/multiselect.js', [
			'jquery',
			'jch-adminutility-js',
			'jch-platformwordpress-js'
		], JCH_VERSION, true );
		wp_register_script( 'jch-smartcombine-js', JCH_PLUGIN_URL . 'media/core/js/smart-combine.js', [
			'jquery',
			'jch-adminutility-js',
			'jch-platformwordpress-js'
		], JCH_VERSION, true );
		wp_register_script( 'jch-chosen-js', JCH_PLUGIN_URL . 'media/chosen-js/chosen.jquery.js', [ 'jquery' ], JCH_VERSION, true );
		wp_register_script( 'jch-filetree-js', JCH_PLUGIN_URL . 'media/filetree/jquery.filetree.js', [ 'jquery' ], JCH_VERSION, true );

		if ( JCH_PRO )
		{
			wp_register_style( 'jch-progressbar-css', JCH_PLUGIN_URL . 'media/jquery-ui/jquery-ui.css', [], JCH_VERSION );

			wp_register_script( 'jch-optimizeimage-js', JCH_PLUGIN_URL . 'media/core/js/ioptimize-api.js', [
				'jquery',
				'jch-adminutility-js',
				'jch-platformwordpress-js'
			], JCH_VERSION, true );
			wp_register_script( 'jch-progressbar-js', JCH_PLUGIN_URL . 'media/jquery-ui/jquery-ui.js', [ 'jquery' ], JCH_VERSION, true );
		}

		$aSettingsArray = TabSettings::getSettingsArray();

		foreach ( $aSettingsArray as $section => $aSettings )
		{
			add_settings_section( 'jch-optimize_' . $section . '_section', '', [
				'\\JchOptimize\\Helper\\Renderer\\Section',
				$section
			], 'jchOptimizeOptionsPage' );

			/**
			 * $aArgs = [
			 *        0 => title,
			 *        1 => description,
			 *        2 => new
			 * ]
			 */
			foreach ( $aSettings as $setting => $aArgs )
			{
				list( $title, $description, $new ) = array_pad( $aArgs, 3, 0 );

				$id    = 'jch-optimize_' . $setting;
				$title = Html::description( $title, $description, $new );
				$args  = [];

				$aClasses = self::getSettingsClassMap();

				if ( isset( $aClasses[$setting] ) )
				{
					$args['class'] = $aClasses[$setting];
				}

				add_settings_field( $id, $title, [
					'\\JchOptimize\\Helper\\Renderer\\Setting',
					$setting
				], 'jchOptimizeOptionsPage', 'jch-optimize_' . $section . '_section', $args );
			}
		}
	}

	private static function getSettingsClassMap()
	{
		return [
			'pro_http2_file_types' => 'jch-wp-checkboxes-grid-wrapper columns-4',
			'staticfiles'          => 'jch-wp-checkboxes-grid-wrapper columns-5',
			'pro_staticfiles_2'    => 'jch-wp-checkboxes-grid-wrapper columns-5',
			'pro_staticfiles_3'    => 'jch-wp-checkboxes-grid-wrapper columns-5'
		];
	}

	public static function addMenuToAdminBar( $admin_bar )
	{
		if ( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		$aArgs = [
			'id'    => 'jch-optimize-parent',
			'title' => 'JCH Optimize'
		];

		$admin_bar->add_node( $aArgs );

		$aArgs = [
			'parent' => 'jch-optimize-parent',
			'id'     => 'jch-optimize-settings',
			'title'  => __( 'Settings', 'jch-optimize' ),
			'href'   => add_query_arg( [
				'page' => 'jch_optimize',
			], admin_url( 'options-general.php' ) )
		];

		$admin_bar->add_node( $aArgs );

		$aArgs = [
			'parent' => 'jch-optimize-parent',
			'id'     => 'jch-optimize-cache',
			'title'  => __( 'Clean Cache', 'jch-optimize' ),
			'href'   => add_query_arg( [
				'page'   => 'jch_optimize',
				'task'   => 'cleancache',
				'return' => base64_encode_url( self::getCurrentAdminUri() )
			], admin_url( 'options-general.php' ) )
		];

		$admin_bar->add_node( $aArgs );
	}

	public static function getCurrentAdminUri()
	{
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri )
		{
			return '';
		}

		return $uri;
	}

	public static function checkMessages()
	{
		if ( get_transient( 'jch-optimize_notices' ) )
		{
			add_action( 'admin_notices', [ __CLASS__, 'publishAdminNotices' ] );
		}
	}

	public static function publishAdminNotices()
	{
		$oContainer = new Container();

		try
		{
			/** @var \JchOptimize\Admin\Application $oApplication */
			$oApplication = Application::getInstance( 'JchOptimize', $oContainer );
			$oApplication->publishMessages( $oApplication->getMessageQueue() );
		}
		catch ( \JchOptimize\Awf\Exception\App $e )
		{
		}
	}

	public static function loadActionLinks( $links, $file )
	{
		static $this_plugin;

		if ( ! $this_plugin )
		{
			$this_plugin = plugin_basename( JCH_PLUGIN_FILE );
		}

		if ( $file == $this_plugin )
		{
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=jch_optimize' ) . '">' . __( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	public static function doAjaxFileTree()
	{
		echo Ajax::getInstance( 'FileTree' )->run();
		die();
	}

	public static function doAjaxMultiSelect()
	{
		echo Ajax::getInstance( 'MultiSelect' )->run();
		die();
	}

	public static function doAjaxOptimizeImages()
	{
		//Got to load the application because we need to enqueue messages
		$oContainer = new Container();
		$oContainer->input->set( 'view', 'optimizeimages' );
		$oContainer->input->set( 'task', 'optimizeimage' );

		$oApplication = Application::getInstance( 'JchOptimize', $oContainer );
		$oApplication->dispatch();
	}

	public static function doAjaxSmartCombine()
	{
		echo Ajax::getInstance( 'SmartCombine' )->run();
		die();
	}
}