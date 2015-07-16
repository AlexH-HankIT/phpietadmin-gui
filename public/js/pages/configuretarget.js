define(['jquery', 'mylibs'], function ($, mylibs) {
    var Methods;

    return Methods = {
        hide_menu: function () {
            $(document).ready(function() {
                $('#configuretargetmenu').hide();
            });
        },
        add_event_handler_targetselection: function () {
            $(document).ready(function(){
                var targetselection = $('#targetselection');

                $(document).once('change', '#targetselection', function () {
                    var defaultvalue = targetselection.find('#default').val();
                    var iqn = targetselection.find("option:selected").val();
                    var configuretargetmenu = $('#configuretargetmenu');

                    // display menu
                    configuretargetmenu.show();

                    if (iqn !== defaultvalue) {
                        mylibs.loadconfiguretargetbody('/phpietadmin/targets/maplun', '', $('#configuretargetlunsadd'))
                    } else {
                        $('#configuretargetbody').html('');
                        configuretargetmenu.hide();
                    }
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