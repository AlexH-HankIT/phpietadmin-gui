define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    return {
        deleteSessionButtonQtip: function () {
            $('.deleteSessionButton').qtip({
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
        deleteSessionButton: function () {
            var iqn = $('#targetSelect').find('option:selected').val(),
                bodyId = '/session';

            $('.deleteSessionButton').once('click', function () {
                var url = require.toUrl('../targets/configure/') + iqn + bodyId,
                    $button = $(this);
                $button.button('loading');
                $.ajax({
                    url: url,
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: {
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
                                mylibs.loadConfigureTargetBody(bodyId, iqn);
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
            });
        }
    };
});