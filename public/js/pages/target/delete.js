define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    return {
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
                    var url = '/phpietadmin/targets/configure';

                    $.ajax({
                        url: url + '/' + $('#targetSelect').find('option:selected').val() + '/delete',
                        data: {
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
                                    return mylibs.load_workspace(url);
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
        },
        add_qtip: function() {
            $('#icon_force').qtip({
                content: {
                    text: 'Delete target even if in use. Requires \'Delete acl\'. ' +
                          'Does not work, if a \'ALL\' initiator acl is configured for this or all targets!'
                },
                style: {
                    classes: 'qtip-youtube'
                }
            });
        }
    };
});