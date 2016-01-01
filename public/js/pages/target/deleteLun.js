define(['jquery', 'mylibs', 'sweetalert', 'bootstrapSelect'], function ($, mylibs, swal) {
    return {
        add_event_handler_deletelunbutton: function () {
            var $deleteLunSelect = $('#deleteLunSelect');
            $deleteLunSelect.selectpicker();
            $('#deleteLunButton').once('click', function () {
                var selected = $deleteLunSelect.find('option:selected'),
                    iqnVal = $('#targetSelect').find('option:selected').val();
                $.ajax({
                    url: require.toUrl('../targets/configure/' + iqnVal + '/deletelun'),
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: {
                        'path': selected.text()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        if (data['code'] === 0) {
                            swal({
                                title: 'Success',
                                type: 'success',
                                text: data['message']
                            }, function() {
                                mylibs.loadConfigureTargetBody('#deletelun', iqnVal);
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
