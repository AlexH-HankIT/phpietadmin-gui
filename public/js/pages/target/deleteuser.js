define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_deleteuserbutton: function () {
            $(document).once('click', '#deleteuserbutton', function () {
                var userdeletecheckbox = $('.userdeletecheckbox:checked');
                if (!userdeletecheckbox.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user'
                    });
                } else {
                    userdeletecheckbox.each(function () {
                        var $this = $(this);
                        var $this_row = $this.closest('tr');

                        $.ajax({
                            url: '/phpietadmin/targets/configure/deleteuser',
                            data: {
                                'iqn': $('#target_selector').find('option:selected').val(),
                                'id': $this_row.find('.id').text(),
                                'type': $this_row.find('.type').text()
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
                                        $this_row.remove();
                                        if ($('#delete_user_table_body').length === 1) {
                                            $('#delete_user_panel').replaceWith('<div class="alert alert-warning" role="alert"><h3 align="center">Error - No users set for this target!</h3></div>');
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
            });
        }
    };
});