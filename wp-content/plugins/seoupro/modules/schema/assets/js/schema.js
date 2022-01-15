jQuery(function ($) {
    $(document).ready(function () {
        schemas.sort(function (a, b) {
            return a.title.localeCompare(b.title);
        });
        ko.applyBindings(new ViewModelPage(schemas));
        $('#ko-ready').show();
    });

});