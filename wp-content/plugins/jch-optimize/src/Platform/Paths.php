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

namespace JchOptimize\Platform;

defined( '_WP_EXEC' ) or die( 'Restricted access' );

use JchOptimize\Core\Helper;
use JchOptimize\Core\Interfaces\Paths as PathsInterface;
use JchOptimize\Core\Url;

class Paths implements PathsInterface
{

	/**
	 * Find the absolute path to a resource given a root relative path
	 *
	 * @param   string  $url  Root relative path of resource on the site
	 *
	 * @return string
	 */
	public static function absolutePath( $url )
	{
		return str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, self::rootPath() ) . DIRECTORY_SEPARATOR .
		       trim( str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $url ), '\\/' );
	}

	/**
	 * @return string Absolute path to root of site
	 */
	public static function rootPath()
	{
		return untrailingslashit( ABSPATH );
	}

	/**
	 * Returns root relative path to the /assets/ folder
	 *
	 * @param   bool  $pathonly
	 *
	 * @return string
	 */
	public static function relAssetPath( $pathonly = false )
	{
		if ( $pathonly )
		{
			return Helper::getBaseFolder() . 'jch-optimize/media/assets';
		}

		return plugins_url() . '/jch-optimize/media/assets';
	}

	/**
	 * The base folder for rewrites when the combined files are delivered with PHP using mod_rewrite. Generally the parent directory for the
	 * /media/ folder with a root relative path
	 *
	 * @return string
	 */
	public static function rewriteBaseFolder()
	{
		static $rewrite_base;

		if ( ! isset( $rewrite_base ) )
		{
			$uri          = Uri::getInstance( plugins_url() );
			$rewrite_base = trailingslashit( $uri->toString( array( 'path' ) ) );
		}

		return $rewrite_base;
	}

	/**
	 * Path to the directory where generated sprite images are saved
	 *
	 * @param   bool  $bRootRelative  If true, return the root relative path; if false, return the absolute path.
	 *
	 * @return string
	 */
	public static function spritePath( $bRootRelative = false )
	{
		if ( $bRootRelative )
		{
			return Helper::getBaseFolder() . 'jch-optimize/media/sprites';
		}

		return JCH_PLUGIN_DIR . 'media/sprites';
	}

	/**
	 * Convert the absolute filepath of a resource to a url
	 *
	 * @param   string  $sPath  Absolute path of resource
	 *
	 * @return string
	 */
	public static function path2Url( $sPath )
	{
		$oUri        = clone Uri::getInstance();
		$sBaseFolder = Helper::getBaseFolder();

		$abs_path = str_replace( DIRECTORY_SEPARATOR, '/', self::rootPath() );
		$sPath    = str_replace( DIRECTORY_SEPARATOR, '/', $sPath );

		$sUriPath = $oUri->toString( array( 'scheme', 'user', 'pass', 'host', 'port' ) ) . $sBaseFolder .
		            ( str_replace( $abs_path . DIRECTORY_SEPARATOR, '', $sPath ) );

		return $sUriPath;
	}

	/**
	 * Url to access Ajax functionality
	 *
	 * @param   string  $function  Action to be performed by Ajax function
	 *
	 * @return string
	 */
	public static function ajaxUrl( $function )
	{
		return add_query_arg( array( 'action' => $function ), admin_url( 'admin-ajax.php' ) );
	}

	/**
	 * Url used in administrator settings page to perform certain tasks
	 *
	 * @param   string  $name
	 *
	 * @return string
	 */
	public static function adminController( $name )
	{
		return add_query_arg( array( 'task' => $name ), admin_url( 'options-general.php?page=jch_optimize' ) );
	}

	/**
	 * Parent directory of the folder where the original images are backed up in the Optimize Image Feature
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function backupImagesParentDir()
	{
		$wp_filesystem = Cache::getWPFileSystem();

		return $wp_filesystem->wp_content_dir();
	}

	/**
	 * Returns path to the directory where static combined css/js files are saved.
	 *
	 * @param   bool  $bRootRelative  If true, returns root relative path, otherwise, the absolute path
	 *
	 * @return string
	 */
	public static function cachePath( $bRootRelative = true )
	{
		if ( $bRootRelative )
		{
			return content_url() . '/cache/jch-optimize';
		}
		else
		{
			return untrailingslashit( JCH_CACHE_DIR );
		}

	}

	public static function nextGenImagesPath( $bRootRelative = false )
	{
		$wp_upload_dir     = wp_upload_dir( null, true, true );
		$sRelJchUploadPath = '/jch-optimize/ng';

		if ( $bRootRelative )
		{
			return Url::toRootRelative( $wp_upload_dir['baseurl'] . $sRelJchUploadPath );
		}

		return $wp_upload_dir['basedir'] . $sRelJchUploadPath;
	}

	public static function iconsUrl()
	{
		return JCH_PLUGIN_URL . 'media/core/icons';
	}
}
