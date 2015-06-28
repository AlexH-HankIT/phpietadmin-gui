define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    $(function() {
        $('.sessiondeletebutton').qtip({
            content: {
                text: 'Normally the initiator immediately reconnects. ' +
                      'To disconnect an initiator permanently you have to deleted the acl allowing the connection ' +
                      'before deleting the session.'
            },
            style: {
                classes: 'qtip-youtube'
            }
        });

        $(document).on('click', '.sessiondeletebutton', function() {
            var data = {
                iqn: $('#targetselection').find("option:selected").val(),
                cid: $(this).closest('tr').find('.cid').text(),
                sid: $(this).closest('tr').find('.sid').text()
            };

            request = mylibs.doajax('/phpietadmin/targets/deletesession', data);

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
                    mylibs.loadconfiguretargetbody('/phpietadmin/targets/deletesession', $('#targetselection').find("option:selected").val());
                }
            });
        });
    });
});