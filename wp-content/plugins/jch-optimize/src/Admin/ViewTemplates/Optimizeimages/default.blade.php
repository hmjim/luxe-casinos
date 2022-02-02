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

use JchOptimize\Core\Admin\Icons;

$url  = admin_url( 'options-general.php' );
$page = add_query_arg(
	[
		'page' => 'jch_optimize',
		'view' => 'optimizeimages',
		'task' => 'optimizeimage'
	], $url
);


$aAutoOptimize = [
	[
		'link'    => '',
		'icon'    => 'auto_optimize.png',
		'name'    => __( 'Optimize Images', 'jch-optimize' ),
		'script'  => 'onclick="jchIOptimizeApi.optimizeImages(\'' . $page . '\', \'auto\'); return false;"',
		'id'      => 'auto-optimize-images',
		'class'   => '',
		'proonly' => true
	]
];

$aManualOptimize = [
	[
		'link'    => '',
		'icon'    => 'manual_optimize.png',
		'name'    => __( 'Optimize Images', 'jch-optimize' ),
		'script'  => 'onclick="jchIOptimizeApi.optimizeImages(\'' . $page . '\', \'manual\'); return false;"',
		'id'      => 'manual-optimize-images',
		'class'   => '',
		'proonly' => true
	]
];
?>
<div class="grid">
    <div class="g-col-12 g-col-lg-6">
        <div id="api2-utilities-block" class="admin-panel-block">
            <h4>{{__('Optimize Image Utility Settings','jch-optimize')}}</h4>
            <p class="alert alert-secondary">{{__('Hover over each title for additional description')}}</p>
            <div class="icons-container">
                {{ Icons::printIconsHTML(Icons::compileUtilityIcons(Icons::getApi2utilityArray()))}}
            </div>
        </div>
    </div>
    <div class="g-col-12 g-col-lg-6">
        <div id="auto-optimize-block" class="admin-panel-block">
            <h4>{{__('Automatic Optimize Image Option', 'jch-optimize')}}</h4>
            <p class="alert alert-secondary">{{__('JCH Optimize will scan the pages of your site for images to optimize. (Currently only the Main Menu).', 'jch-optimize')}}</p>
            <div class="icons-container">
                {{Icons::printIconsHTML($aAutoOptimize)}}
            </div>
        </div>
    </div>
    <div class="g-col-12">
        <script>
            jQuery(document).ready(function () {
                jQuery("#file-tree-container").fileTree(
                    {
                        root: "",
                        script: ajaxurl + '?action=filetree',
                        expandSpeed: 1000,
                        collapseSpeed: 1000,
                        multiFolder: false
                    }, function (file) {
                    });
            });
        </script>
        <div id="manual-optimize-block" class="admin-panel-block">
            <div id="api2-optimize-images-container"></div>
            <div id="optimize-images-container">
                <h4>{{__('Manual Optimize Image Option', 'jch-optimize')}}</h4>
                <p class="alert alert-secondary">{{__('Use the file tree to select the subfolders and files you want to optimize. Files will be optimized in subfolders recursively by default, you can disable this. If you want to rescale your images while optimizing, enter the new width and height in the respective columns beside each image on the right hand side.', 'jch-optimize')}}</p>
                <div class="grid">
                    <div class="g-col-12 g-col-lg-3 g-col-xl-4">
                        <div id="file-tree-container"></div>
                    </div>
                    <div class="g-col-12 g-col-lg-6 g-col-xl-6">
                        <div id="files-container"></div>
                    </div>
                    <div class="g-col-12 g-col-lg-3 g-col-xl-2">
                        <div class="icons-container">
                            <div class="">{{Icons::printIconsHTML($aManualOptimize)}}</div>
                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>
</div>