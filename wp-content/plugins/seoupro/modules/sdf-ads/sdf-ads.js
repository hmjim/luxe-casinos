/*global jQuery:false, alert */
(function ($) {
    "use strict";
    jQuery(document).ready(function ($) {

        $('#sdf-promo-carousel').hide();
        $('#sdf_dashboard_widget .inside').hide();
        var sds_promo_blog_post = $('#sds_promo_blog_post').html();
        var banners_remote = ({
            "dashboard_widget": [
                {
                    "title": "Learn How to Use SEO Ultimate",
                    "content": "<p>Get access to <a rel=\"nofollow\" target=\"_blank\" title=\"SEO Ultimate video training\" href=\"https://seodesignframework.leadpages.net/seo-ultimate-video-training/\">detailed video training</a> covering each module.</p><a rel=\"nofollow\" target=\"_blank\" title=\"SEO Ultimate video training\" href=\"https://seodesignframework.leadpages.net/seo-ultimate-video-training/\"><img src=\"" + suModulesSdfAdsSdfAdsL10n.sdf_banners_url + "SEO-VideoTraining-Banner-v3.jpg\" alt=\"SEO Ultimate video training\" /></a>"
                }
            ]
        })

        // dashboard widget
        //$('#sdf_dashboard_widget h3.hndle span').html(banners_remote.dashboard_widget[0].title);
        //$('#sdf_dashboard_widget .inside').html(banners_remote.dashboard_widget[0].content);
        setTimeout(function () {
            //$('#sdf_dashboard_widget .inside').fadeIn(400);
        }, 800);

    });

})(jQuery);

/**
 * Randomize array element order in-place.
 * Using Fisher-Yates shuffle algorithm.
 */
function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}