define(['jquery', 'mylibs', 'sweetalert', 'bootstrapSelect'], function ($, mylibs, swal) {
    return {
        add_event_handler_maplunbutton: function () {
            var $logical_volume_selector = $('#logical_volume_selector');
            $logical_volume_selector.selectpicker();
            $('#map_lun_button').once('click', function () {
                    var selected = $logical_volume_selector.find("option:selected"),
                        iqnVal = $('#targetSelect').find('option:selected').val();
                    $.ajax({
                        url: require.toUrl('../targets/configure/' + iqnVal + '/maplun'),
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'type': $('#type').find('option:selected').val(),
                            'mode': $('#mode').find('option:selected').val(),
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
                                }, function () {
                                    mylibs.loadConfigureTargetBody('#maplun', iqnVal);
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
            );
        }
    };
});