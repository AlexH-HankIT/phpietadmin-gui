define(['jquery', 'mylibs'], function($, mylibs) {
    $(function() {
        $(document).on('click', '.workspacetab', function() {
            $('.workspacedirect').html('');
            mylibs.loadworkspace($(this), '/phpietadmin/' + $(this).attr('name'));
        });
    });
});