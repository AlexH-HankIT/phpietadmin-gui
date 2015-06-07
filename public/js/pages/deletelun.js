define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        // ajax requests for deletelun
        deleteluniqnselection = $('#deleteluniqnselection');

        deleteluniqnselection.on('change', function () {
            if (deleteluniqnselection.find('option:selected').val() != "default") {
                var data = {
                    "iqn": deleteluniqnselection.find('option:selected').val()
                };

                request = mylibs.doajax("/phpietadmin/targets/deletelun", data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        deletelunluns = $('#deletelunluns');

                        deletelunluns.html(request.responseText);

                        if(deletelunluns.is(':hidden')) {
                            deletelunluns.show();
                        }
                    }
                });
            } else {
                $('#deletelunluns').hide();
            }
        });

        $(document).on('click', '#deletelunbutton', function() {
            var deletelunlunselection = $('#deletelunlunselection');

            var data = {
                "iqn": deleteluniqnselection.find('option:selected').val(),
                "lun": deletelunlunselection.find('option:selected').val(),
                "path": deletelunlunselection.find('option:selected').next().val()
            };

            request = mylibs.doajax("/phpietadmin/targets/deletelun", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == "Success") {
                        swal({
                                title: 'Success',
                                type: 'success'
                            },
                            function () {
                                location.reload();
                            });
                    } else {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: request.responseText
                        });
                    }
                }
            })
        });
    });
});