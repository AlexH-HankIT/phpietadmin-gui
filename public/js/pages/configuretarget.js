define(['jquery', 'mylibs'], function ($, mylibs) {
    var Methods;

    return Methods = {
        hide_menu: function () {
            $(document).ready(function() {
                $('#configure_target_menu').hide();
            });
        },
        add_event_handler_targetselection: function () {
            $(document).ready(function(){
                var targetselection = $('#targetselection');
                var defaultvalue = targetselection.find('#default').val();
                var configuretargetmenu = $('#configure_target_menu');
                var ajaxloader = $('#ajaxloader');
                var ajax_error_sign = $('#ajax_error_sign');

                $(document).once('change', '#targetselection', function () {
                    var iqn = targetselection.find("option:selected").val();

                    // display menu
                    if (iqn !== defaultvalue) {
                        configuretargetmenu.show();

                        return mylibs.load_configure_target_body(iqn, '/phpietadmin/targets/configure/maplun');
                    } else {
                        $('#configure_target_body_wrapper').html('');
                        configuretargetmenu.hide();
                    }
                });

                $(document).once('click', '.configure_target_tab', function() {
                    var $this = $(this);
                    var link = $this.attr('href');
                    var iqn = targetselection.find("option:selected").val();

                    return mylibs.load_configure_target_body(iqn, link, $this);
                });
            });
        },
        add_event_handler_configuretargetnodata: function () {
            $(document).ready(function(){
                $(document).once('click', '.configuretargetnodata', function (e) {
                    mylibs.loadconfiguretargetbody($(this).attr('href'), '', $(this));
                    e.preventDefault();
                });
            });
        },
        add_event_handler_configuretargetiqn: function () {
            $(document).ready(function(){
                $(document).once('click', '.configuretargetiqn', function (e) {
                    var iqn = $('#targetselection').find("option:selected").val();
                    mylibs.loadconfiguretargetbody($(this).attr('href'), iqn, $(this));
                    e.preventDefault();
                });
            });
        }
    };
});