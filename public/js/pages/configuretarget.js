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

            $(document).off('change', '#targetselection');
                $(document).on('change', '#targetselection', function () {
                    var defaultvalue = targetselection.find('#default').val();
                    var iqn = targetselection.find("option:selected").val();
                    var configuretargetmenu = $('#configuretargetmenu');

                    // display menu
                    configuretargetmenu.show();

                    if (iqn !== defaultvalue) {
                        mylibs.loadconfiguretargetbody('targets/maplun', '', $('#configuretargetlunsadd'))
                    } else {
                        $('#configuretargetbody').html('');
                        configuretargetmenu.hide();
                    }
                });
            });
        },
        add_event_handler_configuretargetnodata: function () {
            $(document).ready(function(){
                $(document).off('click', '.configuretargetnodata');
                $(document).on('click', '.configuretargetnodata', function () {
                    mylibs.loadconfiguretargetbody($(this).attr('name'), '', $(this))
                });
            });
        },
        add_event_handler_configuretargetiqn: function () {
            $(document).ready(function(){
                $(document).off('click', '.configuretargetiqn');
                $(document).on('click', '.configuretargetiqn', function () {
                    var iqn = $('#targetselection').find("option:selected").val();
                    mylibs.loadconfiguretargetbody($(this).attr('name'), iqn, $(this))
                });
            });
        }
    };
});