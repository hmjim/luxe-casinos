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

namespace JchOptimize\Core\Admin\Ajax;

use JchOptimize\Core\Admin\MultiSelectItems;
use JchOptimize\Core\Exception;
use JchOptimize\Core\Admin\Json;
use JchOptimize\Platform\Html;
use JchOptimize\Platform\Plugin;

defined( '_JCH_EXEC' ) or die( 'Restricted access' );

class SmartCombine extends Ajax
{
	public function run()
	{
		$oParams = Plugin::getPluginParams();
		$oAdmin  = new MultiSelectItems( $oParams );
		$oHtml   = new Html( $oParams );

		try
		{
			$aHtml       = $oHtml->getMainMenuItemsHtmls();
			$aLinksArray = array();

			foreach ( $aHtml as $sHtml )
			{
				$aLinks = $oAdmin->generateAdminLinks( $sHtml, '', true );

				$aLinks['css'] = $this->setUpArray( $aLinks['css'][0] );
				$aLinks['js']  = $this->setUpArray( $aLinks['js'][0] );

				$aLinksArray[] = $aLinks;
			}

			$aReturnArray = array(
				'css' => $aLinksArray[0]['css'],
				'js'  => $aLinksArray[0]['js']
			);

			for ( $i = 1; $i < count( $aLinksArray ); $i ++ )
			{
				$aReturnArray['css'] = array_filter( array_intersect( $aReturnArray['css'], $aLinksArray[$i]['css'] ), function ( $sUrl ) {
					return ! preg_match( '#fonts\.googleapis\.com#i', $sUrl );
				} );
				$aReturnArray['js']  = array_intersect( $aReturnArray['js'], $aLinksArray[$i]['js'] );
			}
		}
		catch ( Exception $oException )
		{
			return new Json( array() );
		}

		return new Json( $aReturnArray );
	}

	protected function setUpArray( $aLinks )
	{
		return array_map(
			function ( $sValue ) {
				return preg_replace( '#[?\#].*+#i', '', $sValue );
			},
			array_column( $aLinks, 'url' )
		);

	}
}