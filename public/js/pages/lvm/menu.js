define(['jquery', 'mylibs', 'bootstrapSelect'], function ($, mylibs) {
    return {
        add_event_handler: function () {
            var $logicalVolumeSelector = $('#logical_volume_selector'),
                $configureLvmMenu = $('#configure_lvm_menu');

            $logicalVolumeSelector.selectpicker();

            $logicalVolumeSelector.once('change', function () {
                $configureLvmMenu.fadeIn('fast');

                mylibs.load_lvm_target_body(require.toUrl('../lvm/configure/extent'));

                $configureLvmMenu.find('ul').children('li').removeClass('active').each(function () {
                    $(this).removeClass('active');
                });

                $('#configure_lvm_extent').addClass('active');

                return false;
            });

            $('.configure_lvm_body_tab').once('click', function () {
                var $this = $(this);
                return mylibs.load_lvm_target_body($this.attr('href'), $this);
            });
        }
    };
});