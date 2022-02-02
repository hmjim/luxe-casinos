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

namespace JchOptimize\Admin\View\Main;

use Awf\Mvc\View;
use JchOptimize\Admin\Model\Main;

class Html extends View
{
	protected $size = 0;
	protected $no_files = 0;

	protected function onBeforeMain()
	{
		wp_register_script( 'jch-resizesensor-lib-js', JCH_PLUGIN_URL . 'media/css-element-queries/ResizeSensor.js', [ 'jquery' ], JCH_VERSION, true );
		wp_register_script( 'jch-resizesensor-js', JCH_PLUGIN_URL . 'media/core/js/resize-sensor.js', [ 'jch-resizesensor-lib-js' ], JCH_VERSION, true );

		wp_enqueue_script( 'jch-resizesensor-lib-js' );
		wp_enqueue_script( 'jch-resizesensor-js' );

		$this->getCacheSize();

		return true;
	}

	private function getCacheSize()
	{
		/** @var Main $oModel */
		$oModel = $this->getModel();

		$oModel->getCacheSize( JCH_CACHE_DIR, $this->size, $this->no_files );

		$decimals = 2;
		$sz       = 'BKMGTP';
		$factor   = (int) floor( ( strlen( $this->size ) - 1 ) / 3 );

		$this->size     = sprintf( "%.{$decimals}f", $this->size / pow( 1024, $factor ) ) . $sz[$factor];
		$this->no_files = number_format( $this->no_files );
	}
}