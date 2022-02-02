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


namespace JchOptimize\Platform;

defined('_WP_EXEC') or die('Restricted access');

use JchOptimize\Core\Helper;
use JchOptimize\Core\Interfaces\Profiler as ProfilerInterface;

class Profiler implements ProfilerInterface
{

	/**
	 *
	 * @return string
	 */
	protected static function getAdminBarNodeBegin()
	{

		return '<li id="wp-admin-bar-root-default" class="menupop">' .
			'<a class="ab-item" aria-haspopup="true">' .
			'<span class="ab-icon dashicons-clock" style="padding-top: 5px;"></span>' .
			'<span class="ab-label">Profiler (JCH Optimize)</span>' .
			'</a>' .
			'<div class="ab-sub-wrapper">' .
			'<ul id="wp-admin-bar-jch-profiler-items" class="ab-submenu" style="overflow:auto;max-width:700px;max-height:500px;">';
	}

	/**
	 *
	 * @return string
	 */
	protected static function getAdminBarNodeEnd()
	{
		return '</ul></div><li>';
	}

	/**
	 *
	 * @param   string  $item
	 *
	 * @return string
	 */
	protected static function addAdminBarItem($item)
	{
		return '<li id="wp-admin-bar-jch-profiler-item1">' .
			'<a class="ab-item">' . $item . '</a>' .
			'</li>';
	}

	/**
	 *
	 * @staticvar string $item
	 *
	 * @param   string|true  $text
	 *
	 * @return string
	 */
	public static function mark($text)
	{
		static $item = '';

		if ($text === true)
		{
			return $item;
		}

		static $last_time = 0;

		$current_time = (float)timer_stop();

		$time_taken = $last_time > 0 ? $current_time - $last_time : 0;
		$time_taken = (function_exists('number_format_i18n')) ? number_format_i18n($time_taken, 3) : number_format($time_taken, 3);

		$last_time = $current_time;

		$item .= self::addAdminBarItem($current_time . '  (+' . $time_taken . ') - ' . $text);

		return;
	}

	/**
	 *
	 * @param   string  $sHtml
	 * @param   bool    $bAmpPage
	 */
	public static function attachProfiler(&$sHtml, $bAmpPage = false)
	{
		if (!is_super_admin() || $bAmpPage)
		{
			return;
		}

		$items = Profiler::mark(true);

		$node = self::getAdminBarNodeBegin() . $items . self::getAdminBarNodeEnd();

		$script = '<script type="application/javascript">' .
			(Helper::isXhtml($sHtml) ? '/*<![CDATA[*/' : '') .
			'var ul = document.getElementById("wp-admin-bar-root-default");' .
			'if (ul !== null){' .
			'ul.insertAdjacentHTML(\'beforeend\', \'' . $node . '\');}' .
			(Helper::isXhtml($sHtml) ? '/*]]>*/' : '') .
			'</script>';

		$sHtml = str_replace('</body>', $script . '</body>', $sHtml);
	}

	/**
	 *
	 * @param   string  $text
	 * @param   bool    $mark
	 */
	public static function start($text, $mark = false)
	{
		if ($mark)
		{
			self::mark('before' . $text);
		}
	}

	/**
	 *
	 * @param   string  $text
	 * @param   bool    $mark
	 */
	public static function stop($text, $mark = false)
	{
		if ($mark)
		{
			self::mark('after' . $text);
		}
	}

}
