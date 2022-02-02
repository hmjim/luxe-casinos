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

class Configure extends Controller
{
	public function __construct( Container $container = null )
	{
		parent::__construct( $container );
	}

	public function applyautosetting()
	{
		/** @var \JchOptimize\Admin\Model\Configure $oModel */
		$oModel = $this->getModel();

		if ( $oModel->applyautosetting() )
		{
			$this->setMessage( __( 'Settings saved', 'jch-optimize' ), 'success' );
		}
		else
		{
			$this->setMessage( __( 'Error saving settings', 'jch-optimize' ), 'error' );
		}

		$this->setRedirect( 'options-general.php?page=jch_optimize' );

		$this->redirect();
	}

	public function togglesetting()
	{
		/** @var \JchOptimize\Admin\Model\Configure $oModel */
		$oModel = $this->getModel();

		if ( $oModel->togglesetting() )
		{
			$this->setMessage( __( 'Settings saved', 'jch-optimize' ), 'success' );
		}
		else
		{
			$this->setMessage( __( 'Error saving settings', 'jch-optimize' ), 'error' );
		}

		$this->setRedirect( 'options-general.php?page=jch_optimize' );

		$this->redirect();
	}
}