define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;
    return methods = {
        add_event_handler_deletelunbutton: function () {
            $('#delete_lun_button').once('click', function () {
                var $delete_lun_selector = $('#delete_lun_selector'),
                    selected = $delete_lun_selector.find('option:selected');

                $.ajax({
                    url: '/phpietadmin/targets/configure/' + $('#targetSelect').find('option:selected').val() + '/deletelun',
                    data: {
                        'path': selected.attr('data-path')
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
                                // remove selected element
                                selected.remove();

                                if ($delete_lun_selector.find('option').length === 0) {
                                    $('#configure_target_body').replaceWith('<div id="configure_target_body">' +
                                    '<div class = "container">' +
                                    '<div class="alert alert-warning" role="alert"><h3 align="center">Error - No lun available!</h3></div>' +
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
