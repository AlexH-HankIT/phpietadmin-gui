define(['jquery', 'mylibs'], function ($, mylibs) {
    var Methods;

    return Methods = {
        add_event_handler: function () {
            $(function() {
                var logical_volume_selector = $('#logical_volume_selector');
                var default_value = logical_volume_selector.find('#default').val();
                var configure_lvm_menu = $('#configure_lvm_menu');

                configure_lvm_menu.hide();

                logical_volume_selector.once('change', function () {
                    var lv = logical_volume_selector.find("option:selected").attr('data-lv');
                    var configure_lvm_extent = $('#configure_lvm_extent');

                    if (lv !== default_value) {
                        configure_lvm_menu.show();

                        mylibs.load_lvm_target_body('/phpietadmin/lvm/configure/extent');

                        configure_lvm_menu.find('ul').children('li').removeClass('active').each(function() {
                            $(this).removeClass('active');
                        });

                        configure_lvm_extent.addClass('active');

                        return false;
                    } else {
                        $('#configure_lvm_body_wrapper').html('');
                        configure_lvm_menu.hide();
                    }
                });

                $('.configure_lvm_body_tab').once('click', function() {
                    var $this = $(this);
                    return mylibs.load_lvm_target_body($this.attr('href'), $this);
                });
            });
        }
    };
});