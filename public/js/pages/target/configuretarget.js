define(['jquery', 'mylibs'], function ($, mylibs) {
    var Methods;

    return Methods = {
        add_event_handler: function () {
            $(document).ready(function(){
                var target_selection = $('#target_selector');
                var default_value = target_selection.find('#default').val();
                var configure_target_menu = $('#configure_target_menu');

                // hide menu
                configure_target_menu.hide();

                target_selection.once('change', function () {
                    var iqn = target_selection.find("option:selected").val();
                    var configure_target_map_lun = $('.configure_target_map_lun');

                    // display menu
                    if (iqn !== default_value) {
                        configure_target_menu.show();

                        mylibs.load_configure_target_body('/phpietadmin/targets/configure/maplun');

                        configure_target_menu.find('ul').children('li').removeClass('active').each(function() {
                            $(this).removeClass('active');
                        });

                        configure_target_map_lun.addClass('active');

                        return false;
                    } else {
                        $('#configure_target_body_wrapper').html('');
                        configure_target_menu.hide();
                    }
                });

                $('.configure_target_body_tab').once('click', function() {
                    var $this = $(this);
                    return mylibs.load_configure_target_body($this.attr('href'), $this);
                });
            });
        }
    };
});