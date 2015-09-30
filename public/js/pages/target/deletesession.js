define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_qtip_sessiondeletebutton: function () {
            $('.session_delete_button').qtip({
                content: {
                    text: 'Normally the initiator immediately reconnects. ' +
                    'To disconnect an initiator permanently you have to delete the acl allowing the connection ' +
                    'before deleting the session.'
                },
                style: {
                    classes: 'qtip-youtube'
                }
            });
        },
        add_event_handler_sessiondeletebutton: function () {
            $('.session_delete_button').once('click', function () {
                var url = '/phpietadmin/targets/configure/deletesession';
                $.ajax({
                    url: url,
                    data: {
                        iqn: $('#target_selector').find("option:selected").val(),
                        sid: $(this).closest('tr').find('.sid').text()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        if (data['code'] === 0) {
                            swal({
                                title: 'Success',
                                type: 'success',
                                text: data['message']
                            }, function () {
                                mylibs.load_configure_target_body(url);
                            });
                        } else {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: data['message']
                            });
                        }
                    },
                    error: function () {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Something went wrong while submitting!'
                        });
                    }
                });
            });
        }
    };
});