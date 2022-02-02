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

namespace JchOptimize\Admin\View\Configurations;

use Awf\Mvc\View;

class Html extends View
{
	protected function onBeforeMain()
	{
		wp_register_script( 'jch-tabstate-js', JCH_PLUGIN_URL . 'media/js/tabs-state.js', [
			'jquery',
			'jch-bootstrap-js'
		], JCH_VERSION, true );

		wp_enqueue_script( 'jch-tabstate-js' );

		return true;
	}
}