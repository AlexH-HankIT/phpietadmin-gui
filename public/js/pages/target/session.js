define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    return {
        deleteSessionButton: function () {
            var iqn = $('#targetSelect').find('option:selected').val(),
                bodyId = '/session';
            $('[data-toggle="popover"]').popover();
            $('.deleteSessionButton').once('click', function () {
                var url = require.toUrl('../targets/configure/') + iqn + bodyId,
                    $button = $(this);
                $button.button('loading');
                $.ajax({
                    url: url,
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: {
                        sid: $button.closest('tr').find('.sid').text()
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
                                $button.popover('hide')
                            });
                        } else {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: data['message']
                            }, function() {
                                $button.button('reset');
                                $button.popover('hide')
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
                            $button.popover('hide')
                        });
                    }
                });
            });
        }
    };
});