define(['jquery', 'targetMenu'], function ($, targetMenu) {
    var methods;

    return methods = {
        load: function() {
            var $targetSelect = $('#targetSelect');

            targetMenu.initialLoad($($targetSelect));
            $targetSelect.once('change', function () {
                targetMenu.initialLoad($(this));
            });
        }
    };
});