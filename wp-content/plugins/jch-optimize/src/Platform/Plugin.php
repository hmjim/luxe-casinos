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

use JchOptimize\Core\Interfaces\Plugin as PluginInterface;

defined( '_WP_EXEC' ) or die( 'Restricted access' );

class Plugin implements PluginInterface
{

	protected static $plugin = null;

	/**
	 *
	 * @return void
	 */
	public static function getPluginId()
	{
		return;
	}

	/**
	 *
	 * @return void
	 */
	public static function getPlugin()
	{
		return;
	}

	/**
	 *
	 * @param   Settings  $params
	 */
	public static function saveSettings( $params )
	{
		$options = $params->getOptions();

		update_option( 'jch-optimize_settings', $options );
	}

	/**
	 *
	 * @return Settings
	 */
	public static function getPluginParams()
	{
		static $params = null;

		if ( is_null( $params ) )
		{
			$options = get_option( 'jch-optimize_settings' );
			$params  = Settings::getInstance( $options );
		}

		return $params;
	}

}
