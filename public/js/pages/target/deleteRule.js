define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        checkboxes: function() {
            mylibs.select_all_checkbox($('#masterCheckbox'));
        }
    };
});