jQuery(function ($) {
    $(document).ready(function () {

        /* JUGGERNAUT Content Links */
        $('th.su-content-autolinks-link-options.su-link-options .my-check').change(function () {
            if ($('th.su-content-autolinks-link-options.su-link-options .my-check').attr('checked')) {
                $('.su-content-autolinks-link input:checkbox').attr('checked', 'checked');
            } else {
                $('.su-content-autolinks-link input:checkbox').removeAttr('checked');
            }
        });

        /* JUGGERNAUT Footer Links */
        $('th.su-footer-autolinks-link-options.su-link-options .my-check').change(function () {
            if ($('th.su-footer-autolinks-link-options.su-link-options .my-check').attr('checked')) {
                $('.su-footer-autolinks-link td.su-link-options input:checkbox').attr('checked', 'checked');
            } else {
                $('.su-footer-autolinks-link td.su-link-options input:checkbox').removeAttr('checked');
            }
        });

    });
});