define(['jquery'], function($) {
    return {
        test: function () {
            var $container = $('div.container'),
                $createFirstUserButton = $('#createFirstUserButton'),
                $createDatabaseButton = $('#createDatabaseButton');

            if ($createDatabaseButton.next('span').hasClass('icon-success')) {
                $createFirstUserButton.attr('disabled', false);
            }

            $createDatabaseButton.on('click', function() {
                var $span = $(this).next('span');

                // If the span has the icon-success class
                // the database is already created
                if (!$span.hasClass('icon-success')) {
                    $.ajax({
                        url: '/phpietadmin/install/database',
                        type: 'get',
                        success: function (data) {
                            if(data.code !== 0) {
                                $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-ok icon-success');
                                $createFirstUserButton.attr('disabled', false);
                            } else {
                                $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                            }
                        },
                        error: function () {
                            $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                        }
                    });
                }
            });

            $container.on('click', '#createFirstUserButton', function() {

            });
        }
    }
});