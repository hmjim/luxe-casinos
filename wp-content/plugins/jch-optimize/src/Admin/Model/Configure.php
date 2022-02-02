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
use JchOptimize\Core\Admin\Ajax\Ajax;
use JchOptimize\Core\Admin\Icons;
use JchOptimize\Platform\Plugin;
use JchOptimize\Platform\Utility;

class Configure extends Model
{
	public function applyautosetting()
	{
		$aAutoParams = Icons::autoSettingsArrayMap();

		$sAutoSetting = $this->container->input->get( 'autosetting', 's1' );

		if ( ! in_array( $sAutoSetting, [ 's1', 's2', 's3', 's4', 's5', 's6' ] ) )
		{
			return false;
		}

		$aSelectedSetting = array_column( $aAutoParams, $sAutoSetting );

		$aSettingsToApply = array_combine( array_keys( $aAutoParams ), $aSelectedSetting );

		$oParams = Plugin::getPluginParams();

		foreach ( $aSettingsToApply as $name => $value )
		{
			$oParams->set( $name, $value );
		}

		$oParams->set( 'combine_files_enable', '1' );

		try
		{
			Plugin::saveSettings( $oParams );

			return true;
		}
		catch ( \Exception $e )
		{
			return false;
		}
	}

	public function togglesetting()
	{
		$setting = $this->container->input->get( 'setting', null );

		if ( is_null( $setting ) )
		{
			return false;
		}

		$oParams         = Plugin::getPluginParams();
		$iCurrentSetting = (int)$oParams->get( $setting );
		$newSetting      = (string)abs( $iCurrentSetting - 1 );

		if ( $setting == 'pro_remove_unused_css' && $newSetting == '1' )
		{
			$oParams->set( 'optimizeCssDelivery_enable', '1' );
		}

		if ( $setting == 'optimizeCssDelivery_enable' && $newSetting == '0' )
		{
			$oParams->set( 'pro_remove_unused_css', '0' );
		}

		if ( $setting == 'pro_smart_combine' )
		{
			if ( $newSetting == '1' )
			{
				$aSmartCombineValues = Ajax::getInstance( 'SmartCombine' )->run();
				$aCssJsFiles         = array_merge( $aSmartCombineValues->data['css'], $aSmartCombineValues->data['js'] );
				$aCssJsFiles         = rawurlencode( json_encode( $aCssJsFiles ) );

				$oParams->set( 'pro_smart_combine_values', $aCssJsFiles );
			}
			else
			{
				$oParams->set( 'pro_smart_combine_values', '' );
			}
		}

		if ( $setting == 'integrated_page_cache_enable' )
		{
			$bCurrentSetting = Utility::isPageCacheEnabled( $oParams );
			$newSetting      = (string)( ! $bCurrentSetting );

			$oParams->set( 'cache_enable', $newSetting );
		}

		$oParams->set( $setting, $newSetting );

		try
		{
			Plugin::saveSettings( $oParams );

			return true;
		}
		catch ( \Exception $e )
		{
			return false;
		}

	}
}