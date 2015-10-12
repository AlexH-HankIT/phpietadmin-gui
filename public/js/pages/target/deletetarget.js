define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_deletetargetbutton: function () {
            $('#deletetargetbutton').once('click', function () {
                var deleteacl;
                if ($('#deleteacl').prop('checked')) {
                    deleteacl = 1;
                } else {
                    deleteacl = 0;
                }

                var force;
                if ($('#force').prop('checked')) {
                    force = 1;
                } else {
                    force = 0;
                }

                if (force === 1 && deleteacl === 0) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Force needs the \'Delete acl\' option!'
                    });
                } else {
                    var url = '/phpietadmin/targets/configure/deletetarget';

                    $.ajax({
                        url: url,
                        data: {
                            "iqn": $('#target_selector').find("option:selected").val(),
                            "delete_acl": deleteacl,
                            "force": force
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
                }
            });
        }
    };
});