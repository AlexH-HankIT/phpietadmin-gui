define(['jquery'], function($) {
    var methods;

    return methods = {
        change_expand_row_button: function() {
            $(document).once('click', '.clickable', function () {
                var $this = $(this).closest('tr').find('.expandrow');

                if ($this.hasClass('minus')) {
                    $this.html('<span class="glyphicon glyphicon-15 glyphicon-plus">').removeClass('minus');
                } else {
                    $this.html('<span class="glyphicon glyphicon-15 glyphicon-minus">').addClass('minus');
                }
            });
        }
    }
});