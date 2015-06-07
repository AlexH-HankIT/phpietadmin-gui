/* Document ready open */
$(function() {
    /* Selectors start */
    var iqninput = $("#iqninput");
    var ietpermissiontargetselection = $('#ietpermissiontargetselection');
    var ietpermissiontargettable = $('#ietpermissiontargettable');
    var maplun = $('#maplunmanuelinput');
    var autoselection = $("#maplunautoselection");

    /* Selectors end */

    /* Used in /phpietadmin/overview/initiators
    ietpermissiontargetselection.on('change', function () {
        if (ietpermissiontargetselection.find('option:selected').val() == "default") {
            ietpermissiontargettable.hide();
        } else {
            if(ietpermissiontargettable.is(':hidden')) {
                ietpermissiontargettable.show();
            }
            var data = {
                "iqn": ietpermissiontargetselection.find('option:selected').val()
            };

            request = doajax("/phpietadmin/overview/initiators", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    $('#ietpermissiontargettable').html(request.responseText);
                }
            });
        }
    });*/


/* Document ready close */
});