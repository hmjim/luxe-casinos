jQuery(function ($) {
    $(document).ready(function () {

        /* Link Mask Generator */
        $('th.su-internal-link-aliases-alias-delete.su-alias-delete .my-check').change(function () {
            if ($('th.su-internal-link-aliases-alias-delete.su-alias-delete .my-check').attr('checked')) {
                $('.su-internal-link-aliases-alias input:checkbox').attr('checked', 'checked');
            } else {
                $('.su-internal-link-aliases-alias input:checkbox').removeAttr('checked');
            }
        });

    });
});