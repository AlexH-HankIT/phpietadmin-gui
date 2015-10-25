define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_deleteuserbutton: function () {
            $(document).once('click', '#deleteuserbutton', function () {
                var $userdeletecheckbox = $('.userdeletecheckbox:checked');
                if (!$userdeletecheckbox.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user'
                    });
                } else {
                    $userdeletecheckbox.each(function () {
                        var $this = $(this);
                        var $this_row = $this.closest('tr');
                        var url = '/phpietadmin/targets/configure/deleteuser';

                        $.ajax({
                            url: url,
                            data: {
                                'iqn': $('#target_selector').find('option:selected').val(),
                                'id': $this_row.find('.id').text(),
                                'type': $this_row.find('.type').text()
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    return mylibs.load_configure_target_body(url);
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