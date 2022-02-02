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

namespace JchOptimize\Helper;

use JchOptimize\Core\Admin\MultiSelectItems;

class Html
{
	/**
	 * @param $key
	 * @param $settingName
	 * @param $defaultValue
	 * @param ...$aArgs
	 *
	 * @return false|mixed|string
	 */
	public static function _( $key, $settingName, $defaultValue, ...$aArgs )
	{
		list( $function, $proOnly ) = static::extract( $key );

		if ( $proOnly && ! JCH_PRO )
		{
			return self::pro_only();
		}

		$aSavedSettings = get_option( 'jch-optimize_settings' );

		if ( ! isset( $aSavedSettings[$settingName] ) )
		{
			$activeValue = $defaultValue;
		}
		else
		{
			$activeValue = $aSavedSettings[$settingName];
		}

		$callable = [ __CLASS__, $function ];

		//prepend $settingName, $activeValue to arguments
		array_unshift( $aArgs, $settingName, $activeValue );

		return call_user_func_array( $callable, $aArgs );
	}

	/**
	 * @param $key
	 *
	 * @return array
	 */
	protected static function extract( $key )
	{
		$parts = explode( '.', $key );

		$function = $parts[0];
		$proOnly  = isset( $parts[1] ) && $parts[1] === 'pro';

		return [ $function, $proOnly ];
	}

	/**
	 * @return string
	 */
	public static function pro_only()
	{
		return '<div><em style="padding: 5px; background-color: white; border: 1px #ccc;">' . __( 'Only available in Pro Version!', 'jch-optimize' ) . '</em></div>';
	}

	/**
	 * @param          $title
	 * @param          $description
	 * @param   false  $new
	 *
	 * @return string
	 */
	public static function description( $title, $description, $new = false )
	{
		$text = '<div class="title">' . $title;

		if ( $description )
		{
			$text .= '<div class="description"><div><p>' . $description . '</p></div></div>';
		}

		if ( $new )
		{
			$text .= ' <span class="badge badge-danger">New!</span>';
		}

		$text .= '</div>';

		return $text;

	}

	/**
	 * @param           $settingName
	 * @param           $activeValue
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function radio( $settingName, $activeValue, $class = '' )
	{
		$checked = 'checked="checked"';
		$no      = '';
		$yes     = '';

		if ( $activeValue == '1' )
		{
			$yes = $checked;
		}
		else
		{
			$no = $checked;
		}

		$noText  = __( 'No', 'jch-optimize' );
		$yesText = __( 'Yes', 'jch-optimize' );

		$radioHtml = <<<HTML
<fieldset id="jch-optimize_settings_{$settingName}" class="btn-group {$class}" role="group" aria-label="Radio toggle button group">
	<input type="radio" id="jch-optimize_settings_{$settingName}0" name="jch-optimize_settings[{$settingName}]" class="btn-check" value="0" {$no} >
	<label for="jch-optimize_settings_{$settingName}0" class="btn btn-outline-secondary">{$noText}</label>
	<input type="radio" id="jch-optimize_settings_{$settingName}1" name="jch-optimize_settings[{$settingName}]" class="btn-check" value="1" {$yes} >
	<label for="jch-optimize_settings_{$settingName}1" class="btn btn-outline-secondary">{$yesText}</label>
</fieldset>
		
HTML;

		if ( $settingName == 'pro_smart_combine' )
		{
			$radioHtml .= self::button( $settingName );
		}

		return $radioHtml;
	}

	/**
	 * @param $settingName
	 *
	 * @return string
	 */
	protected static function button( $settingName )
	{
		$imgUrl = JCH_PLUGIN_URL . 'media/core/images/exclude-loader.gif';

		$buttonHtml = <<<HTML
<img id="img-{$settingName}" src="{$imgUrl}" style="display: none;" >
<button id="btn-{$settingName}" type="button" class="btn btn-outline-secondary" style="display: none;" >Reprocess Smart Combine</button>
HTML;

		return $buttonHtml;
	}

	/**
	 * @param           $settingName
	 * @param           $activeValue
	 * @param           $aOptions
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function select( $settingName, $activeValue, $aOptions, $class = '' )
	{
		$optionsHtml = '';

		foreach ( $aOptions as $key => $value )
		{
			$selected = $activeValue == $key ? ' selected="selected"' : '';

			$optionsHtml .= <<<HTML
<option value="{$key}"{$selected}>$value</option>
HTML;
		}

		$selectHtml = <<<HTML
<select id="jch-optimize_settings_{$settingName}" name="jch-optimize_settings[{$settingName}]" class="chzn-custom-value {$class}">
	{$optionsHtml}
</select>
HTML;

		return $selectHtml;
	}

	/**
	 * @param           $settingName
	 * @param           $aActiveValues
	 * @param           $aOptions
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function multiselect( $settingName, $aActiveValues, $type, $group, $class = '' )
	{
		$optionsHtml = '';

		foreach ( $aActiveValues as $value )
		{
			$option = MultiSelectItems::{'prepare' . ucfirst( $group ) . 'Values'}( $value );

			$optionsHtml .= <<<HTML
<option value="{$value}" selected>$option</option>
HTML;
		}

		$addItemText = __( 'Add item', 'jch-optimize' );
		$imgSrc      = JCH_PLUGIN_URL . 'media/core/images/exclude-loader.gif';

		$multiSelectHtml = <<<HTML
<select id="jch-optimize_settings_{$settingName}" name="jch-optimize_settings[{$settingName}][]" class="jch-multiselect chzn-custom-value {$class}" multiple="multiple" size="5" data-jch_type="{$type}" data-jch_group="{$group}" data-jch_param="{$settingName}" >
	{$optionsHtml}
</select>
<img id="img-{$settingName}" class="jch-multiselect-loading-image" src="{$imgSrc}" />
<button id="btn-{$settingName}" style="display: none;" class="btn btn-secondary btn-sm jch-multiselect-add-button" type="button" onclick="jchMultiselect.addJchOption('jch-optimize_settings_{$settingName}')">{$addItemText}</button>
HTML;

		return $multiSelectHtml;
	}

	/**
	 * @param           $settingName
	 * @param           $activeValue
	 * @param   string  $size
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function text( $settingName, $activeValue,  $size = '30', $class = '' )
	{
		$textInputHtml = <<<HTML
<input type="text" id="jch-optimize_settings_{$settingName}" name="jch-optimize_settings[{$settingName}]" value="{$activeValue}" size="{$size}" class="{$class}">
HTML;

		return $textInputHtml;
	}

	/**
	 * @param           $settingName
	 * @param           $activeValue
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function checkbox( $settingName, $activeValue, $class = '' )
	{
		$checked = $activeValue == '1' ? 'checked="checked"' : '';
		$offText = __( 'No', 'jch-optimize' );
		$onText  = __( 'Yes', 'jch-optimize' );

		$checkBoxHtml = <<<HTML
<input type="checkbox" id="jch-optimize_settings_{$settingName}" class="{$class}" name="jch-optimize_settings[$settingName]" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="{$onText}" data-off="{$offText}" value="1" {$checked}>
HTML;

		return $checkBoxHtml;
	}

	/**
	 * @param           $settingName
	 * @param           $aActiveValues
	 * @param           $aOptions
	 * @param   string  $class
	 *
	 * @return string
	 */
	public static function checkboxes( $settingName, $aActiveValues, $aOptions, $class = '' )
	{
		$optionsHtml = '';
		$i           = '0';

		foreach ( $aOptions as $key => $value )
		{
			$checked = '';

			if ( is_array( $aActiveValues ) && in_array( $key, $aActiveValues ) )
			{
				$checked = 'checked';
			}

			$optionsHtml .= <<<HTML
<li>
	<input type="checkbox" id="jch-optimize_settings_{$settingName}{$i}" name="jch-optimize_settings[$settingName][]" value="{$key}" $checked>
	<label for="jch-optimize_settings_{$settingName}{$i}">{$value}</label>
</li>
HTML;
			$i ++;
		}

		$checkboxesHtml = <<<HTML
<fieldset id="jch-optimize_settings_{$settingName}" class="{$class}">
	<ul>
		{$optionsHtml}
	</ul>
</fieldset>
HTML;

		return $checkboxesHtml;
	}
}