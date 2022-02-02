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

use Awf\Mvc\Controller;
use JchOptimize\Core\Admin\Ajax\Ajax as AdminAjax;

class Optimizeimages extends Controller
{
	public function optimizeimage()
	{
		$status = $this->input->get( 'status', null );

		if ( is_null( $status ) )
		{
			echo AdminAjax::getInstance( 'OptimizeImage' )->run();

			$this->container->application->close();
		}
		else
		{
			if ( $status == 'success' )
			{
				$dir = rtrim( $this->input->get( 'dir', '' ), '/' ) . '/';
				$cnt = $this->input->get( 'cnt', '' );

				$this->setMessage( sprintf( __( '%1$d images optimized in %2$s', 'jch-optimize' ), $cnt, $dir ), 'success' );
			}
			else
			{
				$msg = $this->input->get( 'msg', '' );
				$this->setMessage( __( 'The Optimize Image function failed with message "' . $msg, 'jch-optimize' ), 'error' );
			}

			$this->setRedirect( 'options-general.php?page=jch_optimize&tab=optimizeimage' );
			$this->redirect();
		}
	}
}