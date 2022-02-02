<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/core
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core;

use JchOptimize\Core\Css\Callbacks\ExtractCriticalCss;

defined( '_JCH_EXEC' ) or die( 'Restricted access' );

class DynamicSelectors
{
	public static function getDynamicSelectors( ExtractCriticalCss $oExtractCriticalCss, $aMatches )
	{
		//Add all CSS containing any specified dynamic CSS to the critical CSS
		$aDynamicSelectors = Helper::getArray( $oExtractCriticalCss->oParams->get( 'pro_dynamic_selectors', [] ) );
		$aDynamicSelectors = array_unique( array_merge( $aDynamicSelectors, [ 'offcanvas', 'off-canvas', 'mobilemenu', 'mobile-menu' ] ) );

		if ( ! empty( $aDynamicSelectors ) )
		{
			foreach ( $aDynamicSelectors as $sDynamicSelector )
			{
				if ( strpos( $aMatches[2], $sDynamicSelector ) !== false )
				{
					$oExtractCriticalCss->appendToCriticalCss( $aMatches[0] );

					$oExtractCriticalCss->_debug( '', '', 'afterAddDynamicCss' );

					return true;
				}
			}
		}

		$oExtractCriticalCss->_debug( '', '', 'afterSearchDynamicCss' );

		return false;
	}
}