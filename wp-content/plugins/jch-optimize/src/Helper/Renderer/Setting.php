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

namespace JchOptimize\Helper\Renderer;

use JchOptimize\Helper\Html;

class Setting
{
	public static function pro_downloadid()
	{
		echo Html::_( 'text.pro', 'pro_downloadid', '' );
	}

	public static function debug()
	{
		echo Html::_( 'radio', 'debug', '0' );
	}

	public static function disable_logged_in_users()
	{
		echo Html::_( 'radio', 'disable_logged_in_users', '0' );
	}

	public static function menuexcludedurl()
	{
		echo Html::_( 'multiselect', 'menuexcludedurl', [], 'url', 'file' );
	}


	public static function combine_files_enable()
	{
		echo Html::_( 'radio', 'combine_files_enable', '1' );
	}

	public static function pro_smart_combine()
	{
		echo Html::_( 'radio.pro', 'pro_smart_combine', '0', 'jch-smart-combine-radios-wrapper' );
	}

	public static function cache_lifetime()
	{
		$aOptions = [
			'1800'    => __( '30 min', 'jch-optimize' ),
			'3600'    => __( '1 hour', 'jch-optimize' ),
			'10800'   => __( '3 hours', 'jch-optimize' ),
			'21600'   => __( '6 hours', 'jch-optimize' ),
			'43200'   => __( '12 hours', 'jch-optimize' ),
			'86400'   => __( '1 day', 'jch-optimize' ),
			'172800'  => __( '2 days', 'jch-optimize' ),
			'604800'  => __( '7 days', 'jch-optimize' ),
			'1209600' => __( '2 weeks', 'jch-optimize' )
		];

		echo Html::_( 'select', 'cache_lifetime', '1800', $aOptions );
	}

	public static function html_minify_level()
	{
		$aOptions = [
			'0' => __( 'Basic', 'jch-optimize' ),
			'1' => __( 'Advanced', 'jch-optimize' ),
			'2' => __( 'Ultra', 'jch-optimize' )
		];

		echo Html::_( 'select', 'html_minify_level', '0', $aOptions );
	}

	public static function htaccess()
	{
		$aOptions = [
			'2' => __( 'Static css and js files', 'jch-optimize' ),
			'0' => __( 'PHP file with query', 'jch-optimize' ),
			'1' => __( 'PHP using url re-write', 'jch-optimize' ),
			'3' => __( 'PHP using url re-write (Without Options +FollowSymLinks)', 'jch-optimize' ),
		];

		echo Html::_( 'select', 'htaccess', '2', $aOptions );
	}

	public static function try_catch()
	{
		echo Html::_( 'radio', 'try_catch', '1' );
	}

	public static function gzip()
	{
		echo Html::_( 'radio', 'gzip', '0' );
	}

	public static function html_minify()
	{
		echo Html::_( 'radio', 'html_minify', '0' );
	}

	public static function includeAllExtensions()
	{
		echo Html::_( 'radio', 'includeAllExtensions', '0' );
	}

	public static function phpAndExternal()
	{
		echo Html::_( 'radio', 'phpAndExternal', '0' );
	}

	public static function css()
	{
		echo Html::_( 'radio', 'css', '1' );
	}

	public static function css_minify()
	{
		echo Html::_( 'radio', 'css_minify', '0' );
	}

	public static function replaceImports()
	{
		echo Html::_( 'radio', 'replaceImports', '0' );
	}

	public static function inlineStyle()
	{
		echo Html::_( 'radio', 'inlineStyle', '0' );
	}

	public static function excludeCss()
	{
		echo Html::_( 'multiselect', 'excludeCss', [], 'css', 'file' );
	}

	public static function excludeCssComponents()
	{
		echo Html::_( 'multiselect', 'excludeCssComponents', [], 'css', 'extension' );
	}

	public static function excludeStyles()
	{
		echo Html::_( 'multiselect', 'excludeStyles', [], 'css', 'style' );
	}

	public static function excludeAllStyles()
	{
		echo Html::_( 'radio', 'excludeAllStyles', '0' );
	}

	public static function remove_css()
	{
		echo Html::_( 'multiselect', 'remove_css', [], 'css', 'file' );
	}

	public static function pro_optimize_gfont_enable()
	{
		echo Html::_( 'radio.pro', 'pro_optimize_gfont_enable', '0' );
	}

	public static function optimizeCssDelivery_enable()
	{
		echo Html::_( 'radio', 'optimizeCssDelivery_enable', '0' );
	}

	public static function optimizeCssDelivery()
	{
		$aOptions = [ '200' => '200', '400' => '400', '600' => '600', '800' => '800' ];

		echo Html::_( 'select', 'optimizeCssDelivery', '200', $aOptions );
	}

	public static function pro_remove_unused_css()
	{
		echo Html::_( 'radio.pro', 'pro_remove_unused_css', '0' );
	}

	public static function pro_dynamic_selectors()
	{
		echo Html::_( 'multiselect.pro', 'pro_dynamic_selectors', [], 'selectors', 'style' );
	}

	public static function javascript()
	{
		echo Html::_( 'radio', 'javascript', '1' );
	}

	public static function js_minify()
	{
		echo Html::_( 'radio', 'js_minify', '0' );
	}

	public static function inlineScripts()
	{
		echo Html::_( 'radio', 'inlineScripts', '0' );
	}

	public static function bottom_js()
	{
		echo Html::_( 'radio', 'bottom_js', '0' );
	}

	public static function loadAsynchronous()
	{
		echo Html::_( 'radio', 'loadAsynchronous', '0' );
	}

	public static function excludeJs_peo()
	{
		echo Html::_( 'multiselect', 'excludeJs_peo', [], 'js', 'file' );
	}

	public static function excludeJsComponents_peo()
	{
		echo Html::_( 'multiselect', 'excludeJsComponents_peo', [], 'js', 'extension' );
	}

	public static function excludeScripts_peo()
	{
		echo Html::_( 'multiselect', 'excludeScripts_peo', [], 'js', 'script' );
	}

	public static function excludeAllScripts()
	{
		echo Html::_( 'radio', 'excludeAllScripts', '0' );
	}

	public static function excludeJs()
	{
		echo Html::_( 'multiselect', 'excludeJs', [], 'js', 'file' );
	}

	public static function excludeJsComponents()
	{
		echo Html::_( 'multiselect', 'excludeJsComponents', [], 'js', 'extension' );
	}

	public static function excludeScripts()
	{
		echo Html::_( 'multiselect', 'excludeScripts', [], 'js', 'script' );
	}

	public static function dontmoveJs()
	{
		echo Html::_( 'multiselect', 'dontmoveJs', [], 'js', 'file' );
	}

	public static function dontmoveScripts()
	{
		echo Html::_( 'multiselect', 'dontmoveScripts', [], 'js', 'script' );
	}

	public static function remove_js()
	{
		echo Html::_( 'multiselect', 'remove_js', [], 'js', 'file' );
	}

	public static function cache_enable()
	{
		echo Html::_( 'radio', 'cache_enable', '0' );
	}

	public static function pro_cache_platform()
	{
		echo Html::_( 'radio.pro', 'pro_cache_platform', '0' );
	}

	public static function page_cache_lifetime()
	{
		$aOptions = [
			'900'    => __( '15 min', 'jch-optimize' ),
			'1800'   => __( '30 min', 'jch-optimize' ),
			'3600'   => __( '1 hour', 'jch-optimize' ),
			'10800'  => __( '3 hours', 'jch-optimize' ),
			'21600'  => __( '6 hours', 'jch-optimize' ),
			'43200'  => __( '12 hours', 'jch-optimize' ),
			'86400'  => __( '1 day', 'jch-optimize' ),
			'172800' => __( '2 days', 'jch-optimize' ),
			'604800' => __( '1 week', 'jch-optimize' )
		];

		echo Html::_( 'select', 'page_cache_lifetime', '900', $aOptions );
	}

	public static function cache_exclude()
	{
		echo Html::_( 'multiselect', 'cache_exclude', [], 'url', 'file' );
	}

	public static function img_attributes_enable()
	{
		echo Html::_( 'radio', 'img_attributes_enable', '0' );
	}

	public static function csg_enable()
	{
		echo Html::_( 'radio', 'csg_enable', '0' );
	}

	public static function csg_direction()
	{
		$aOptions = [
			'vertical'   => __( 'vertical', 'jch-optimize' ),
			'horizontal' => __( 'horizontal', 'jch-optimize' )
		];

		echo Html::_( 'select', 'csg_drection', 'vertical', $aOptions );
	}

	public static function csg_wrap_images()
	{
		echo Html::_( 'radio', 'csg_wrap_images', '0' );
	}

	public static function csg_exclude_images()
	{
		echo Html::_( 'multiselect', 'csg_exclude_images', [], 'images', 'file' );
	}

	public static function csg_include_images()
	{
		echo Html::_( 'multiselect', 'csg_include_images', [], 'images', 'file' );
	}

	public static function lazyload_enable()
	{
		echo Html::_( 'radio', 'lazyload_enable', '0' );
	}

	public static function lazyload_autosize()
	{
		echo Html::_( 'radio', 'lazyload_autosize', '1' );
	}

	public static function pro_lazyload_effects()
	{
		echo Html::_( 'radio.pro', 'pro_lazyload_effects', '0' );
	}

	public static function pro_lazyload_iframe()
	{
		echo Html::_( 'radio.pro', 'pro_lazyload_iframe', '0' );
	}

	public static function pro_lazyload_bgimages()
	{
		echo Html::_( 'radio.pro', 'pro_lazyload_bgimages', '0' );
	}

	public static function pro_lazyload_audiovideo()
	{
		echo Html::_( 'radio.pro', 'pro_lazyload_audiovideo', '0' );
	}

	public static function excludeLazyLoad()
	{
		echo Html::_( 'multiselect', 'excludeLazyLoad', [], 'lazyload', 'file' );
	}

	public static function pro_excludeLazyLoadFolders()
	{
		echo Html::_( 'multiselect.pro', 'pro_excludeLazyLoadFolders', [], 'lazyload', 'folder' );
	}

	public static function pro_excludeLazyLoadClass()
	{
		echo Html::_( 'multiselect.pro', 'pro_excludeLazyLoadClass', [], 'lazyload', 'class' );
	}

	public static function http2_push_enable()
	{
		echo Html::_( 'radio', 'http2_push_enable', '0' );
	}

	public static function pro_http2_exclude_deferred()
	{
		echo Html::_( 'radio.pro', 'pro_http2_exclude_deferred', '0' );
	}

	public static function pro_http2_push_cdn()
	{
		echo Html::_( 'radio.pro', 'pro_http2_push_cdn', '0' );
	}

	public static function pro_http2_file_types()
	{
		$aOptions = [
			'style'  => 'style',
			'script' => 'script',
			'font'   => 'font',
			'image'  => 'image'
		];

		echo Html::_( 'checkboxes', 'pro_http2_file_types', array_keys( $aOptions ), $aOptions );
	}

	public static function pro_http2_include()
	{
		echo Html::_( 'multiselect', 'pro_http2_include', [], 'http2', 'file' );
	}

	public static function pro_http2_exclude()
	{
		echo Html::_( 'multiselect', 'pro_http2_exclude', [], 'http2', 'file' );
	}

	public static function cookielessdomain_enable()
	{
		echo Html::_( 'radio', 'cookielessdomain_enable', '0' );
	}

	public static function pro_cdn_preconnect()
	{
		echo Html::_( 'radio.pro', 'pro_cdn_preconnect', '0' );
	}

	public static function cdn_scheme()
	{
		$aOptions = [
			'0' => __( 'scheme relative', 'jch-optimize' ),
			'1' => __( 'http', 'jch-optimize' ),
			'2' => __( 'https', 'jch-optimize' )
		];

		echo Html::_( 'select', 'cdn_scheme', 0, $aOptions );
	}

	public static function cookielessdomain()
	{
		echo Html::_( 'text', 'cookielessdomain', '' );
	}

	public static function staticfiles()
	{
		echo Html::_( 'checkboxes', 'staticfiles', array_keys( self::staticFilesArray() ), self::staticFilesArray() );
	}

	private static function staticFilesArray()
	{
		return [
			'css'   => 'css',
			'png'   => 'png',
			'gif'   => 'gif',
			'ico'   => 'ico',
			'pdf'   => 'pdf',
			'js'    => 'js',
			'jpe?g' => 'jp(e)g',
			'bmp'   => 'bmp',
			'webp'  => 'webp',
			'svg'   => 'svg'
		];
	}

	public static function pro_customcdnextensions()
	{
		echo Html::_( 'multiselect.pro', 'pro_customcdnextensions', [], 'url', 'file' );
	}

	public static function pro_cookielessdomain_2()
	{
		echo Html::_( 'text.pro', 'pro_cookielessdomain_2', '' );
	}

	public static function pro_staticfiles_2()
	{
		echo Html::_( 'checkboxes.pro', 'pro_staticfiles_2', array_keys( self::staticFilesArray() ), self::staticFilesArray() );
	}

	public static function pro_cookielessdomain_3()
	{
		echo Html::_( 'text.pro', 'pro_cookielessdomain_3', '' );
	}

	public static function pro_staticfiles_3()
	{
		echo Html::_( 'checkboxes.pro', 'pro_staticfiles_3', array_keys( self::staticFilesArray() ), self::staticFilesArray() );
	}

	public static function ignore_optimized()
	{
		echo Html::_( 'radio', 'ignore_optimized', '1' );
	}

	public static function pro_next_gen_images()
	{
		echo Html::_( 'radio.pro', 'pro_next_gen_images', '1' );
	}

	public static function pro_web_old_browsers()
	{
		echo Html::_( 'radio.pro', 'pro_web_old_browsers', '0' );
	}

	public static function pro_api_resize_mode()
	{
		echo Html::_( 'radio.pro', 'pro_api_resize_mode', '1' );
	}

	public static function recursive()
	{
		echo Html::_( 'radio', 'recursive', '1' );
	}

	public static function pro_reduce_dom()
	{
		echo Html::_( 'radio.pro', 'pro_reduce_dom', '0' );
	}
}