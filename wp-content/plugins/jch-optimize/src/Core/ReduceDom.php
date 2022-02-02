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

use JchOptimize\Core\Html\Parser as HtmlParser;
use JchOptimize\Platform\Profiler;
use JchOptimize\Platform\Settings;

defined( '_JCH_EXEC' ) or die( 'Restricted access' );

class ReduceDom
{
	public static function process( Settings $oParams, $sHtml )
	{
		if ( ! $oParams->get( 'pro_reduce_dom', '0' ) )
		{
			return $sHtml;
		}

		JCH_DEBUG ? Profiler::start( 'ReduceDom', false ) : null;

		$aOptions = array(
			'num-elements'  => 0, //number of elements encountered
			'nesting-level' => 0,
			'in-comments'   => false, //Inside a section being commented out
			'processing'    => false, //Maximum number of elements reached and DOM is now being reduced
			'html-block'    => '' //Html block currently being processed
		);
		$sRegex   = '#(?:[^<]*+(?:' . HtmlParser::HTML_HEAD_ELEMENT() . '|' . HtmlParser::HTML_COMMENT() . '))?[^<]*+<(/)?(\w++)[^>]*+>#si';

		$sReducedHtml = preg_replace_callback( $sRegex, function ( $aMatches ) use ( &$aOptions ) {
			//Initialize return string
			$sReturn       = '';
			$bEndComments  = false;
			$aHtmlSections = array( 'section', 'header', 'footer', 'aside', 'nav' );

			switch ( true )
			{
				//Open tag
				case ! empty( $aMatches[2] ) && empty( $aMatches[1] ):
					//Increment count of elements
					$aOptions['num-elements']++;

					if ( $aOptions['processing'] && in_array( $aMatches[2], $aHtmlSections )
						&& $aOptions['nesting-level']++ == 0 )
					{
						$sReturn .= '<div class="jch-reduced-dom-container"><template class="jch-template">';

						$aOptions['in-comments'] = true;
						$aOptions['html-block']  = $aMatches[2];
					}

					//Start commenting out sections of HTML above 600 DOM elements
					if ( $aOptions['num-elements'] == 600 )
					{
						$aOptions['processing'] = true;
					}

					break;
				//Closing tag
				case ! empty( $aMatches[1] ):

					if ( $aOptions['in-comments'] && in_array( $aMatches[2], $aHtmlSections ) &&
						--$aOptions['nesting-level'] == 0 && $aMatches[2] == $aOptions['html-block'] )
					{
						$bEndComments = true;
					}

					break;
				default:
					break;
			}

			$sReturn .= $aMatches[0];

			if ( $bEndComments )
			{
				$sReturn .= '</template></div>';

				$aOptions['in-comments'] = false;
			}

			return $sReturn;
		}, $sHtml );

		JCH_DEBUG ? Profiler::stop( 'ReduceDom', true ) : null;

		return $sReducedHtml;

	}
}