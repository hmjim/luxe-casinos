<?php /* /var/www/new_luxe-casinos/data/www/vulkanumm.com/wp-content/plugins/jch-optimize/src/Admin/ViewTemplates/Configurations/default.blade.php */ ?>
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

use JchOptimize\Platform\Plugin;

$oParams            = Plugin::getPluginParams();
$hiddenContainsGF   = $oParams->get( 'hidden_containsgf', '' );
$smartCombineValues = $oParams->get( 'pro_smart_combine_values', '' );

?>
<form action="options.php" method="post" id="jch-optimize-settings-form">
    <div class="grid mt-n3">
        <div class="g-col-12 g-col-md-2">
            <ul class="nav flex-wrap flex-md-column nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#general-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">General</div>
                            <small class="text-wrap d-none d-lg-block">Download ID, Exclude menus, Combine files</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#css-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">CSS</div>
                            <small class="text-wrap d-none d-lg-block">Exclude CSS, Google fonts, Optimize CSS delivery, Remove unused CSS</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#javascript-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Javascript</div>
                            <small class="text-wrap d-none d-lg-block">Optimize JS, Exclude JS, Don't move to bottom, Remove JS</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#page-cache-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Page Cache</div>
                            <small class="text-wrap d-none d-lg-block">Mobile caching, Cache lifetime, Exclude urls</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#media-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Media</div>
                            <small class="text-wrap d-none d-lg-block">Lazy-load, Add image attributes, Sprite generator</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#http2-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Http/2</div>
                            <small class="text-wrap d-none d-lg-block">Push CDN files, Include/Exclude files</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cdn-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">CDN</div>
                            <small class="text-wrap d-none d-lg-block">Preconnect domains, Select file types, 3 Domains</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#optimize-image-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Optimize Images</div>
                            <small class="text-wrap d-none d-lg-block">Webp generation, Optimize by page, Optimize by folders</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#miscellaneous-tab" data-bs-toggle="tab">
                        <div>
                            <div class="fs-6 fw-bold mb-1">Misc<span class="d-md-none d-lg-inline">ellaneous</span></div>
                            <small class="text-wrap d-none d-lg-block">Reduce DOM</small>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="g-col-12 g-col-md-10">
            <?php echo \JchOptimize\Helper\TabContent::start(); ?>


            <?php echo settings_fields('jchOptimizeOptionsPage'); ?>

            <?php echo do_settings_sections('jchOptimizeOptionsPage'); ?>


            <?php echo \JchOptimize\Helper\TabContent::end(); ?>


            <input type="hidden" id="jch-optimize_settings_pro_smart_combine_values"
                   name="jch-optimize_settings[pro_smart_combine_values]" class="jch-smart-combine-values"
                   value="<?php echo $smartCombineValues; ?>">
            <input type="hidden" id="jch-optimize_settings_hidden_containsgf"
                   name="jch-optimize_settings[hidden_containsgf]"
                   value="<?php echo $hiddenContainsGF; ?>">
            <input type="hidden" id="jch-optimize_settings_hidden_api_secret"
                   name="jch-optimize_settings[hidden_api_secret]"
                   value="11e603aa">

            <?php echo submit_button('Save Settings', 'primary large', 'jch-optimize_settings_submit'); ?>

        </div>
    </div>
</form>