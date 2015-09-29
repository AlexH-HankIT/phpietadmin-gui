define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_adduserbutton: function () {
            $('#adduserbutton').once('click', function () {
                var addusercheckbox_checked = $('.addusercheckbox:checked');

                if (!addusercheckbox_checked.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user!'
                    });
                } else {
                    // Select radio
                    var type = $("input[name='type']:checked").val();

                    // loop through checkboxes
                    addusercheckbox_checked.each(function () {
                        var $this = $(this);
                        var url = '/phpietadmin/targets/configure/adduser';

                        $.ajax({
                            url: url,
                            data: {
                                'iqn': $('#target_selector').find('option:selected').val(),
                                'type': type,
                                'id': $this.closest('tr').find('.userid').text()
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
                                        // uncheck all the checkbox
                                        $this.removeAttr('checked');
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    });
                                }
                                mylibs.load_configure_target_body(url);
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