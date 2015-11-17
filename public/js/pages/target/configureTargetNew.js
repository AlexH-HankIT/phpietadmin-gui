define(['jquery', 'mylibs'], function ($, mylibs) {
    var Methods;

    return Methods = {
        add_event_handler: function () {
            function load_configure_target_menu() {

            }

            function load_configure_target_body(link, clicked) {
                $('#configure_target_body').remove();

                if (clicked !== undefined && clicked != '') {
                    $('#configure_target_menu').find('ul').children('li').removeClass('active');
                    clicked.parents('li').addClass('active');
                }

                $('#configureTargetBodyWrapper').load(link, function (response, status) {
                    if (status == 'error') {
                        $(this).html("<div id='configure_target_body'>" +
                        "<div class='container'>" +
                        "<div class='alert alert-warning' role='alert'>" +
                        "<h3 align='center'>" +
                        response +
                        "</h3>" +
                        "</div>" +
                        '</div>' +
                        '</div>');
                    }
                });

                window.history.pushState({path: link}, '', link);

                return false;
            }

            var $targetSelect = $('#targetSelect');
            var default_value = $targetSelect.find('#default').val();
            var configure_target_menu = $('#configure_target_menu');

            $targetSelect.once('change', function () {
                var iqn = $targetSelect.find("option:selected").val();
                var configure_target_map_lun = $('.configure_target_map_lun');

                // display menu
                if (iqn !== default_value) {
                    var url = '/phpietadmin/targets/configure/' + iqn + '/maplun';

                    console.log(url);

                    load_configure_target_body(url);

                    configure_target_menu.find('ul').children('li').removeClass('active').each(function () {
                        $(this).removeClass('active');
                    });

                    configure_target_map_lun.addClass('active');

                    return false;
                } else {
                    $('#configure_target_body_wrapper').html('');
                    configure_target_menu.hide();
                }
            });

            $('.configure_target_body_tab').once('click', function () {
                var $this = $(this);
                return mylibs.load_configure_target_body($this.attr('href'), $this);
            });
        }
    };
});