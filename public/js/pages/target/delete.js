define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        add_event_handler_deletetargetbutton: function () {
            $('.btn.btn-xs.btn-info').popover();

            $('#deleteTargetButton').once('click', function () {
                var deleteacl,
                    $button = $(this);
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
                    var url = require.toUrl('../targets/configure');
                    $button.button('loading');
                    $.ajax({
                        url: url + '/' + $('#targetSelect').find('option:selected').val() + '/delete',
                        beforeSend: mylibs.checkAjaxRunning(),
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
                                }, function() {
                                    $button.button('reset');
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function() {
                                $button.button('reset');
                            });
                        }
                    });
                }
            });
        }
    };
});