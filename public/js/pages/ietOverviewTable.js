define(['jquery'], function($) {
    return {
        expandRow: function() {
            var $workspace = $('.workspace');

            $workspace.once('hidden.bs.collapse', function (e) {
                var $row = $(e.target),
                    $button = $row.prev('tr').find('.expandRow'),
                    $span = $button.children('span'),
                    $icon = $span.first(),
                    $text = $span.last();

                if ($icon.hasClass('glyphicon-minus')) {
                    $icon.removeClass('glyphicon-minus').addClass('glyphicon-plus');
                    $text.html('Show')
                }
            });

            $workspace.once('show.bs.collapse', function (e) {
                var $row = $(e.target),
                    $button = $row.prev('tr').find('.expandRow'),
                    $span = $button.children('span'),
                    $icon = $span.first(),
                    $text = $span.last();

                if ($icon.hasClass('glyphicon-plus')) {
                    $icon.removeClass('glyphicon-plus').addClass('glyphicon-minus');
                    $text.html('Hide')
                }
            });
        }
    }
});