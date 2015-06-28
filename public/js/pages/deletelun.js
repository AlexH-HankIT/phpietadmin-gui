define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('click', '#deletelunbutton', function() {
            var deletelunlunselection = $('#deletelunlunselection');
            var deleteluniqnselection = $('#deleteluniqnselection');
            var iqn = $('#targetselection').find("option:selected").val();

            var data = {
                "iqn": iqn,
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
                            });
                    } else {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: request.responseText
                        });
                    }
                    mylibs.loadconfiguretargetbody('/phpietadmin/targets/deletelun', iqn)
                }
            })
        });
    });
});