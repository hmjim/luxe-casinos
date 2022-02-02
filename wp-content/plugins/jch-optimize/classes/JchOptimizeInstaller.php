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
use JchOptimize\Platform\Cache;

class JchOptimizeInstaller
{


	public function __construct()
	{
	}

	/**
	 * Fires when plugin is activated and create a dir.php file in plugin root containing
	 * absolute path of plugin install
	 *
	 *
	 * @return null
	 */
	public function activate()
	{
		//$this->initializeFileSystem();

		//global $wp_filesystem;

		try
		{
			$wp_filesystem = Cache::getWpFileSystem();
		}
		catch ( Exception $e )
		{
			return false;
		}

		if ( $wp_filesystem === false )
		{
			return false;
		}

		$file    = JCH_PLUGIN_DIR . 'dir.php';
		$abspath = ABSPATH;
		$code    = <<<PHPCODE
<?php
           
\$DIR = '$abspath';
           
PHPCODE;

		$wp_filesystem->put_contents( $file, $code, FS_CHMOD_FILE );
		Tasks::leverageBrowserCaching();
	}

	/**
	 * Initialize the WordPress FileSystem API,
	 *
	 *
	 * @return null
	 */
	protected function initializeFileSystem()
	{

		$access_type = get_filesystem_method();
		if ( $access_type === 'direct' )
		{
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

			/* initialize the API */
			if ( ! \WP_Filesystem( $creds ) )
			{
				/* any problems and we exit */
				return false;
			}

		}
		else
		{
			/* don't have direct write access. Prompt user with our notice */
			//add_action('admin_notices', 'you_admin_notice_function'); 	
		}
	}
}
