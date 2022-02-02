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

var jchPlatform = (function ($) {

    let jch_ajax_url_optimizeimages = ajaxurl + '?action=optimizeimages';
    let jch_ajax_url_smartcombine = ajaxurl + '?action=smartcombine';
    let jch_ajax_url_multiselect = ajaxurl + '?action=multiselect';


    let configure_url = "options-general.php?page=jch_optimize&view=configure";

    var applyAutoSettings = function (int) {
        window.location.href = configure_url + "&task=applyautosetting&autosetting=s" + int;
    }

    var toggleSetting = function (setting) {
        window.location.href = configure_url + "&task=togglesetting&setting=" + setting;
    }

    var submitForm = function () {
        document.getElementById('jch-optimize-settings-form').submit();
    }

    return {
        //properties
        jch_ajax_url_optimizeimages: jch_ajax_url_optimizeimages,
        jch_ajax_url_smartcombine: jch_ajax_url_smartcombine,
        jch_ajax_url_multiselect: jch_ajax_url_multiselect,
        //methods
        applyAutoSettings: applyAutoSettings,
        toggleSetting: toggleSetting,
        submitForm: submitForm
    }

})(jQuery);