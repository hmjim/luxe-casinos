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

namespace JchOptimize\Admin;

use Awf\Container\Container as AwfContainer;

class Container extends AwfContainer
{
	public function __construct( array $values = array() )
	{
		if ( ! isset( $values['application_name'] ) )
		{
			$values['application_name'] = 'JchOptimize';
		}

		if ( ! isset( $values['applicationNamespace'] ) )
		{
			$values['applicationNamespace'] = '\\JchOptimize\\Admin';
		}

		if ( ! isset( $values['filesystemBase'] ) )
		{
			$values['filesystemBase'] = rtrim( ABSPATH, '/\\' );
		}

		if ( ! isset( $values['basePath'] ) )
		{
			$values['basePath'] = JCH_PLUGIN_DIR . 'src/Admin';
		}

		if ( ! isset( $values['templatePath'] ) )
		{
			$values['templatePath'] = JCH_PLUGIN_DIR . 'templates';
		}

		if ( ! isset( $values['temporaryPath'] ) )
		{
			$values['temporaryPath'] = JCH_PLUGIN_DIR . 'tmp';
		}

		if ( ! isset( $values['session_segment_name'] ) )
		{
			$installationId = 'default';

			if ( function_exists( 'base64_encode' ) )
			{
				$installationId = base64_encode( __DIR__ );
			}

			if ( function_exists( 'md5' ) )
			{
				$installationId = md5( __DIR__ );
			}

			if ( function_exists( 'sha1' ) )
			{
				$installationId = sha1( __DIR__ );
			}

			$values['session_segment_name'] = $values['application_name'] . '_' . $installationId;
		}

		$values['db']              = '';
		$values['fileSystem']      = '';
		$values['mailer']          = '';
		$values['session']         = '';
		$values['userManager']     = '';

		parent::__construct( $values );
	}
}