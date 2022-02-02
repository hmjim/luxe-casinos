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

namespace JchOptimize\Admin\Controller;

use Awf\Container\Container;
use Awf\Mvc\Controller;
use JchOptimize\Core\Admin\Tasks;
use JchOptimize\Platform\Cache;

class Utility extends Controller
{
	public function __construct( Container $container = null )
	{
		parent::__construct( $container );
	}

	public function browsercaching()
	{
		$expires = Tasks::leverageBrowserCaching();

		if ( $expires === false )
		{
			$this->setMessage( __( 'Failed trying to add browser caching codes to the .htaccess file', 'jch-optimize' ), 'error' );
		}
		elseif ( $expires === 'FILEDOESNTEXIST' )
		{
			$this->setMessage( __( 'No .htaccess file were found in the root of this site', 'jch-optimize' ), 'warning' );
		}
		elseif ( $expires === 'CODEUPDATEDSUCCESS' )
		{
			$this->setMessage( __( 'The .htaccess file was updated successfully', 'jch-optimize' ), 'success' );
		}
		elseif ( $expires === 'CODEUPDATEDFAIL' )
		{
			$this->setMessage( __( 'Failed to update the .htaccess file', 'jch-optmize' ), 'warning' );
		}
		else
		{
			$this->setMessage( __( 'Successfully added codes to the .htaccess file to promote browser caching', 'jch-optimize' ), 'success' );
		}


		$this->setRedirect( 'options-general.php?page=jch_optimize' );

		$this->redirect();
	}

	public function orderplugins()
	{
		\JchOptimizeLoader::orderPlugin();

		$this->setMessage( __( 'Plugins ordered successfully', 'jch-optimize' ), 'success' );
		$this->setRedirect( 'options-general.php?page=jch_optimize' );

		$this->redirect();
	}

	public function keycache()
	{
		Tasks::generateNewCacheKey();

		$this->setMessage( __( 'New cache key generated!', 'jch-optimize' ), 'success' );
		$this->setRedirect( 'options-general.php?page=jch_optimize' );

		$this->redirect();
	}

	public function cleancache()
	{
		if ( Cache::deleteCache() )
		{
			$this->setMessage( __( 'Cache deleted successfully!', 'jch-optimize' ), 'success' );
		}
		else
		{
			$this->setMessage( __( 'Error cleaning cache!', 'jch-optimize' ), 'error' );
		}

		if ( ( $return = $this->input->get( 'return', '' ) ) != '' )
		{
			$this->setRedirect( base64_decode_url( $return ) );
		}
		else
		{
			$this->setRedirect( 'options-general.php?page=jch_optimize' );
		}

		$this->redirect();
	}

	public function restoreimages()
	{
		$mResult = Tasks::restoreBackupImages();

		if ( $mResult === 'SOMEIMAGESDIDNTRESTORE' )
		{
			$this->setMessage( __( 'Failed restoring all original images', 'jch-optimize' ), 'warning' );
		}
		elseif ( $mResult === 'BACKUPPATHDOESNTEXIST' )
		{
			$this->setMessage( __( 'The folder containing backup images wasn\'t created yet. Try optimizing some images first.', 'jch-optimize' ), 'warning' );
		}
		else
		{
			$this->setMessage( __( 'Successfully restored all images', 'jch-optimize' ), 'success' );
		}

		$this->setRedirect( 'options-general.php?page=jch_optimize&tab=optimizeimages' );

		$this->redirect();
	}

	public function deletebackups()
	{
		$mResult = Tasks::deleteBackupImages();

		if ( $mResult === false )
		{
			$this->setMessage( __( 'Failed trying to delete backup images', 'jch-optimize' ), 'error' );
		}
		elseif ( $mResult === 'BACKUPPATHDOESNTEXIST' )
		{
			$this->setMessage( __( 'The folder containing backup images wasn\'t created yet. Try optimizing some images first.', 'jch-optimize' ), 'warning' );
		}
		else
		{
			$this->setMessage( __( 'Successfully deleted backup images', 'jch-optimize' ), 'success' );
		}

		$this->setRedirect( 'options-general.php?page=jch_optimize&tab=optimizeimages' );

		$this->redirect();
	}
}