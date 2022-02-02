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

use JchOptimize\Platform\Settings;
use JchOptimize\Platform\Utility;

defined( '_JCH_EXEC' ) or die( 'Restricted access' );

class CdnDomains
{
	public static function addCdnDomains( Cdn $oCdn, array &$aDomain )
	{
		if ( trim( $oCdn->oParams->get( 'pro_cookielessdomain_2', '' ) ) != '' )
		{
			$domain2       = $oCdn->oParams->get( 'pro_cookielessdomain_2' );
			$sStaticFiles2 = implode( '|', $oCdn->oParams->get( 'pro_staticfiles_2', Cdn::getStaticFiles() ) );

			$aDomain[ $oCdn->scheme . $oCdn->prepareDomain( $domain2 ) ] = $sStaticFiles2;
		}

		if ( trim( $oCdn->oParams->get( 'pro_cookielessdomain_3', '' ) ) != '' )
		{
			$domain3       = $oCdn->oParams->get( 'pro_cookielessdomain_3' );
			$sStaticFiles3 = implode( '|', $oCdn->oParams->get( 'pro_staticfiles_3', Cdn::getStaticFiles() ) );

			$aDomain[ $oCdn->scheme . $oCdn->prepareDomain( $domain3 ) ] = $sStaticFiles3;
		}
	}

	public static function preconnect( Settings $oParams )
	{
		if ( $oParams->get( 'cookielessdomain_enable', '0' ) && $oParams->get( 'pro_cdn_preconnect', '1' ) )
		{
			$oCdn     = Cdn::getInstance( $oParams );
			$aDomains = $oCdn->getCdnDomains();

			$sCdnPreConnect = '';

			foreach ( $aDomains as $sDomain => $sStaticFiles )
			{
				$sCdnPreConnect .= Utility::tab() . '<link rel="preconnect" href="' . $sDomain . '" crossorigin />'
					. Utility::lnEnd();
			}

			return $sCdnPreConnect;
		}
	}
}