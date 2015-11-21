define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;
    return methods = {
        add_event_handler_maplunbutton: function () {
            $('#map_lun_button').once('click', function () {
                    var $logical_volume_selector = $('#logical_volume_selector');
                    var selected = $logical_volume_selector.find("option:selected");

                    if (selected.attr('id') === 'default') {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a volume!'
                        });
                    } else {
                        $.ajax({
                            url: '/phpietadmin/targets/configure/' + $('#targetSelect').find('option:selected').val() + '/maplun',
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
                                    }, function() {
                                        selected.remove();

                                        if ($logical_volume_selector.find('option').length === 1) {
                                            $('#configure_target_body').replaceWith('<div id="configure_target_body">' +
                                            '<div class = "container">' +
                                            '<div class="alert alert-warning" role="alert"><h3 align="center">Error - No logical volumes available!</h3></div>' +
                                            '</div>' +
                                            '</div>')
                                        }
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    });
                                }
                            },
                            error: function (data) {
                                console.log(data);
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    }
                }
            );
        }
    };
});