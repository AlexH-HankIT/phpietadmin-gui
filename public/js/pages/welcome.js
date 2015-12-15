define(['jquery'], function($) {
    return {
        test: function () {
            var $container = $('div.container');

            $container.on('click', '#createDatabaseButton', function() {
                var $span = $(this).next('span');
                $.ajax({
                    url: '/phpietadmin/install/database',
                    type: 'get',
                    success: function (data) {
                        if(data.code !== 0) {
                            $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-ok icon-success');
                        } else {
                            $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                        }
                    },
                    error: function () {
                        $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                    }
                });
            });

            $container.on('click', '#createFirstUserButton', function() {

            });
        }
    }
});