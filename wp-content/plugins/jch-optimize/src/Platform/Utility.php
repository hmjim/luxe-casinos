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

use JchOptimize\Core\Interfaces\Settings;
use JchOptimize\Core\Interfaces\Utility as UtilityInterface;

defined( '_WP_EXEC' ) or die( 'Restricted access' );

class Utility implements UtilityInterface
{

	/**
	 *
	 * @param   string  $text
	 *
	 * @return string
	 */
	public static function translate( $text )
	{
		return __( $text, 'jch-optimize' );
	}

	/**
	 *
	 * @return integer
	 */
	public static function unixCurrentDate()
	{
		return current_time( 'timestamp', true );
	}

	/*
	 *
	 */

	public static function getEditorName()
	{
		return '';
	}

	/**
	 *
	 * @param   string  $message
	 * @param   string  $priority
	 * @param   string  $filename
	 */
	public static function log( $message, $priority, $filename )
	{
		$file = Utility::getLogsPath() . '/jch-optimize.log';

		error_log( $message . "\n", 3, $file );
	}

	/**
	 *
	 */
	public static function getLogsPath()
	{
		return JCH_PLUGIN_DIR . 'logs';
	}

	/**
	 *
	 * @return string
	 */
	public static function lnEnd()
	{
		return "\n";
	}

	/**
	 *
	 * @return string
	 */
	public static function tab()
	{
		return "\t";
	}

	/**
	 *
	 * @param   string  $value
	 *
	 * @return string
	 */
	public static function decrypt( $value )
	{
		return self::encrypt_decrypt( $value, 'decrypt' );
	}

	/**
	 *
	 * @param   string  $value
	 * @param   string  $action
	 *
	 * @return string
	 */
	private static function encrypt_decrypt( $value, $action )
	{

		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key     = AUTH_KEY;
		$secret_iv      = AUTH_SALT;

		// hash
		$key = hash( 'sha256', $secret_key );

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if ( $action == 'encrypt' )
		{
			if ( version_compare( PHP_VERSION, '5.3.3', '<' ) )
			{
				$output = @openssl_encrypt( $value, $encrypt_method, $key, 0 );
			}
			else
			{
				$output = openssl_encrypt( $value, $encrypt_method, $key, 0, $iv );
			}
			$output = base64_encode( $output );
		}
		else if ( $action == 'decrypt' )
		{
			if ( version_compare( PHP_VERSION, '5.3.3', '<' ) )
			{
				$output = @openssl_decrypt( base64_decode( $value ), $encrypt_method, $key, 0 );
			}
			else
			{
				$output = openssl_decrypt( base64_decode( $value ), $encrypt_method, $key, 0, $iv );
			}
		}

		return $output;
	}

	/**
	 *
	 * @param   string  $value
	 *
	 * @return string
	 */
	public static function encrypt( $value )
	{
		return self::encrypt_decrypt( $value, 'encrypt' );
	}

	/**
	 *
	 *
	 * @param   string  $value
	 * @param   string  $default
	 * @param   string  $filter
	 * @param   string  $method
	 *
	 * @return mixed
	 */
	public static function get( $value, $default = '', $filter = 'cmd', $method = 'request' )
	{
		$request = '_' . strtoupper( $method );

		if ( ! isset( $GLOBALS[$request][$value] ) )
		{
			$GLOBALS[$request][$value] = $default;
		}

		switch ( $filter )
		{
			case 'int':
				$filter = FILTER_SANITIZE_NUMBER_INT;

				break;

			case 'array':
			case 'json':
				return (array) $GLOBALS[$request][$value];
			case 'string':
			case 'cmd':
			default :
				$filter = FILTER_SANITIZE_STRING;

				break;
		}

		switch ( $method )
		{
			case 'get':
				$type = INPUT_GET;

				break;

			case 'post':
				$type = INPUT_POST;

				break;

			default:

				return filter_var( $_REQUEST[$value], $filter );
		}


		$input = filter_input( $type, $value, $filter );

		return is_null( $input ) ? $default : $input;
	}

	/**
	 *
	 * @param   string  $url
	 */
	public static function loadAsync( $url )
	{

	}

	/**
	 *
	 */
	public static function menuId()
	{

	}

	/**
	 * Checks if user is not logged in
	 *
	 */
	public static function isGuest()
	{
		return ! is_user_logged_in();
	}


	public static function sendHeaders( $headers )
	{
		if ( ! empty( $headers ) )
		{
			foreach ( $headers as $header => $value )
			{
				header( $header . ': ' . $value, false );
			}
		}

	}

	public static function userAgent( $userAgent )
	{
		global $is_chrome, $is_IE, $is_edge, $is_safari, $is_opera, $is_gecko, $is_winIE, $is_macIE, $is_iphone;

		$oUA                 = new \stdClass();
		$oUA->browser        = 'Unknown';
		$oUA->browserVersion = 'Unknown';
		$oUA->os             = 'Unknown';

		if ( $is_chrome )
		{
			$oUA->browser = 'Chrome';
		}
		elseif ( $is_gecko )
		{
			$oUA->browser = 'Firefox';
		}
		elseif ( $is_safari )
		{
			$oUA->browser = 'Safari';
		}
		elseif ( $is_edge )
		{
			$oUA->browser = 'Edge';
		}
		elseif ( $is_IE )
		{
			$oUA->browser = 'Internet Explorer';
		}
		elseif ( $is_opera )
		{
			$oUA->browser = 'Opera';
		}


		if ( $oUA->browser != 'Unknown' )
		{

			// Build the REGEX pattern to match the browser version string within the user agent string.
			$pattern = '#(?<browser>Version|' . $oUA->browser . ')[/ :]+(?<version>[0-9.|a-zA-Z.]*)#';

			// Attempt to find version strings in the user agent string.
			$matches = array();

			if ( preg_match_all( $pattern, $userAgent, $matches ) )
			{
				// Do we have both a Version and browser match?
				if ( \count( $matches['browser'] ) == 2 )
				{
					// See whether Version or browser came first, and use the number accordingly.
					if ( strripos( $userAgent, 'Version' ) < strripos( $userAgent, $oUA->browser ) )
					{
						$oUA->browserVersion = $matches['version'][0];
					}
					else
					{
						$oUA->browserVersion = $matches['version'][1];
					}
				}
				elseif ( \count( $matches['browser'] ) > 2 )
				{
					$key = array_search( 'Version', $matches['browser'] );

					if ( $key )
					{
						$oUA->browserVersion = $matches['version'][$key];
					}
				}
				else
				{
					// We only have a Version or a browser so use what we have.
					$oUA->browserVersion = $matches['version'][0];
				}
			}

		}

		if ( $is_winIE )
		{
			$oUA->os = 'Windows';
		}
		elseif ( $is_macIE )
		{
			$oUA->os = 'Mac';
		}
		elseif ( $is_iphone )
		{
			$oUA->os = 'iOS';
		}


		return $oUA;
	}

	public static function bsTooltipContentAttribute()
	{
		return 'data-bs-content';
	}

	public static function isPageCacheEnabled( Settings $oParams )
	{
		return (bool)$oParams->get('cache_enable', '0');
	}
}
