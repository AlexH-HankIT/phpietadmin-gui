define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        seltargetselection = $('#targetselection');
        defaultvalue = seltargetselection.find('#default').val();

        $(document).on('change', '#targetselection', function(){
            var iqn = seltargetselection.find("option:selected").val();

            // Check that iqn is not default
            if (iqn !== defaultvalue) {
                var data = {
                    "iqn": iqn
                };

                request = mylibs.doajax("/phpietadmin/permission/deleteuser", data);

                request.done(function() {
                    if (request.readyState == 4 && request.status == 200) {
                            $('#deleteusertablediv').html(request.responseText);
                    }
                });
            } else {
                // Delete table if iqn is default
                $('#deleteusertablediv').html('');
            }
        });

        $(document).on('click', '#deleteuserbutton', function(){
            var iqn = seltargetselection.find("option:selected").val();

            if (iqn !== defaultvalue) {
                var userdeletecheckbox = $(".userdeletecheckbox:checked");
                if (!userdeletecheckbox.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user'
                    });
                } else {
                    userdeletecheckbox.each(function () {
                        // iqn, user, type
                        var data = {
                            "iqn": seltargetselection.find("option:selected").val(),
                            "user": $(this).closest('tr').find('.user').text(),
                            "type": $(this).closest('tr').find('.type').text()
                        };

                        request = mylibs.doajax('/phpietadmin/permission/deleteuser', data);

                        request.done(function() {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == true) {
                                    swal({
                                            title: 'Success',
                                            type: 'success'
                                        });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: request.responseText
                                    });
                                }
                                mylibs.loadconfiguretargetbody('/phpietadmin/permission/deleteuser', iqn);
                            }
                        });
                    });
                }
            } else {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a iqn!'
                });
            }
        });
    });
});

