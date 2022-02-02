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

use Awf\Dispatcher\Dispatcher as AwfDispatcher;

class Dispatcher extends AwfDispatcher
{
	public function __construct( $container = null )
	{
		parent::__construct( $container );
	}

}