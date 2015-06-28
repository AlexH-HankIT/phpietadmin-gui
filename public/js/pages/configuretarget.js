define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var Methods;
    var targetselection = $('#targetselection');

    return Methods = {
        hide_menu: function () {
            $('#configuretargetmenu').hide();
        },
        add_event_handler_targetselection: function () {
            $(document).on('change', '#targetselection', function () {
                var defaultvalue = targetselection.find('#default').val();
                var iqn = targetselection.find("option:selected").val();
                var configuretargetmenu = $('#configuretargetmenu');

                // display menu
                configuretargetmenu.show();

                if (iqn !== defaultvalue) {
                    mylibs.loadconfiguretargetbody($('#configuretargetlunsadd'), '/phpietadmin/targets/maplun')
                } else {
                    $('#configuretargetbody').html('');
                    configuretargetmenu.hide();
                }
            });
        },
        add_event_handler_configuretargetnodata: function () {
            $(document).on('click', '.configuretargetnodata', function () {
                mylibs.loadconfiguretargetbody($(this), '/phpietadmin/' + $(this).attr('name'))
            });
        },
        add_event_handler_configuretargetiqn: function () {
            $(document).on('click', '.configuretargetiqn', function () {
                var iqn = targetselection.find("option:selected").val();
                mylibs.loadconfiguretargetbody($(this), '/phpietadmin/' + $(this).attr('name'), iqn)
            });
        }
    };
});