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

$aToggleIcons         = Icons::compileToggleFeaturesIcons( Icons::getToggleSettings() );
$aAdvancedToggleIcons = Icons::compileToggleFeaturesIcons( Icons::getAdvancedToggleSettings() );

?>
<div id="control-panel-block" class="grid" style="grid-template-rows: auto;">
    <div class="g-col-12 g-col-lg-8" style="grid-row-end: span 2;">
        <div id="combine-files-block" class="admin-panel-block">
            <h4>{{ __('Combine Files Automatic Settings', 'jch-optimize') }}</h4>
            <p class="alert alert-secondary">{{ __('Choose one of the six presets to automatically configure the settings concerned with the optimization of CSS and javascript files. You can also disable the combine files feature here and exclude files on the Configurations tab.', 'jch-optimize') }}</p>
            <div class="icons-container">
                {{Icons::printIconsHTML(Icons::compileToggleFeaturesIcons(Icons::getCombineFilesEnableSetting()))}}
                <div class="icons-container">
                    {{Icons::printIconsHTML(Icons::compileAutoSettingsIcons(Icons::getAutoSettingsArray()))}}
                </div>
            </div>
        </div>
    </div>
    <div class="g-col-12 g-col-lg-4" style="grid-row-end: span 3">
        <div id="utility-settings-block" class="admin-panel-block">
            <h4>{{__('Utility Tasks', 'jch-optimize')}}</h4>
            <p class="alert alert-secondary">{{__('Some useful utility tasks. Hover over each title for more description.', 'jch-optimize')}}</p>
            <div>
                <div class="icons-container">
                    {{Icons::printIconsHTML(Icons::compileUtilityIcons(Icons::getUtilityArray(['browsercaching', 'orderplugins', 'keycache'])))}}
                    <div class="icons-container">
                        {{Icons::printIconsHTML(Icons::compileUtilityIcons(Icons::getUtilityArray(['cleancache'])))}}
                        <div>
                            <br>
                            <div>
                                <em>{{sprintf( __('No of files: %s', 'jch-optimize'), $this->no_files )}}</em>
                            </div>
                            <div>
                                <em>{{sprintf( __('Size of files: %s', 'jch-optimize'), $this->size )}}</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="g-col-12 g-col-lg-8" style="grid-row-end: span 3;">
        <div id="toggle-settings-block" class="admin-panel-block">
            <h4>{{__('Basic Features','jch-optimize')}}</h4>
            <p class="alert alert-secondary">{{__('Click each button to toggle these features on/off individually. Enable the ones you need for your site. Some may need additional configuration. You can access these settings from the Configurations tab.', 'jch-optimize')}}</p>
            <div>
                <div class="icons-container">
                    {{Icons::printIconsHTML($aToggleIcons)}}
                </div>
            </div>
        </div>
    </div>
    <div class="g-col-12 g-col-lg-4" style="grid-row-end: span 2;">
        <div id="advanced-settings-block" class="admin-panel-block">
            <h4>{{__('Advanced Settings','jch-optimize')}}</h4>
            <p class="alert alert-secondary">{{__('Click each button to toggle these features on/off individually. Enable the ones you need for your site. Some may need additional configuration. You can access these settings from the Configurations tab.', 'jch-optimize')}}</p>
            <div>
                <div class="icons-container">
                    {{Icons::printIconsHTML($aAdvancedToggleIcons)}}
                </div>
            </div>
        </div>
    </div>
    <div class="g-col-12">
        <div id="copyright-block" class="admin-panel-block">
            <strong>JCH Optimize Pro {{JCH_VERSION}}</strong> Copyright 2021 &copy; <a
                    href="https://www.jch-optimize.net/">JCH Optimize</a>
        </div>
    </div>
</div>
<!-- for testing
<script>
    window.onload = function(){
        var el = document.querySelector('#intermediate span.hasPopover');
        var popover = new bootstrap.Popover(el, {html: true});
        popover.show();
    }
</script>

-->