define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_adduserbutton: function () {
            mylibs.select_all_checkbox($('#master_checkbox'));

            $('#adduserbutton').once('click', function () {
                var checkboxes = $('.addusercheckbox:checked');

                if (!checkboxes.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user!'
                    });
                } else {
                    checkboxes.each(function () {
                        var $this = $(this),
                            bodyId = '/adduser',
                            iqn = $('#targetSelect').find('option:selected').val();

                        $.ajax({
                            url: '/phpietadmin/targets/configure/' + iqn + bodyId,
                            data: {
                                'type': $("input[name='type']:checked").val(),
                                'id': $this.closest('tr').find('.userId').text()
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    });
                                    checkboxes.removeAttr('checked');
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