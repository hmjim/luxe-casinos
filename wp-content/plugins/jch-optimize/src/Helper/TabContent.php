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

class TabContent
{
	public static function start()
	{
		return <<<HTML
<div class="tab-content">
	<div style="display:none">
		<fieldset>
			<div>
HTML;

	}

	public static function addTab( $id, $active = false )
	{
		$active = $active ? ' active' : '';

		return <<<HTML
			</div>
		</fieldset>
	</div>		
	<div class="tab-pane{$active}" id="{$id}">
		<fieldset style="display: none;">
			<div>
HTML;

	}

	public static function addSection( $header = '', $description = '', $class = '' )
	{
		if ( ! empty( $header ) )
		{
			$header = <<<HMTL
<legend>{$header}</legend>
HMTL;
		}

		return <<<HTML
			</div>
		</fieldset>
		<fieldset class="jch-group">
			{$header}
			<div class="{$class}"><p><em>{$description}</em></p></div>
			<div>		
HTML;
	}

	public static function end()
	{
		return <<<HTML
			</div>
		</fieldset>
	</div>
</div>
HTML;
	}
}