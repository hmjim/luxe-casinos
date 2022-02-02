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

namespace JchOptimize\Admin\Model;

use Awf\Mvc\Model;
use FilesystemIterator;

class Main extends Model
{
	public function getCacheSize( $cache_path, &$size, &$no_files )
	{
		if ( file_exists( $cache_path ) )
		{
			$fi = new FilesystemIterator( $cache_path, FilesystemIterator::SKIP_DOTS );

			foreach ( $fi as $file )
			{
				$size += $file->getSize();
			}

			$no_files += iterator_count( $fi );
		}
	}
}