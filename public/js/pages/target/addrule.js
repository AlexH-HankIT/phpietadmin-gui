define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;
    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});

        },
        add_event_handler_addallowrulebutton: function () {
            mylibs.select_all_checkbox($('#master_checkbox'));

            $('#add_allow_rule_button').once('click', function () {
                var checkboxes = $('.object_checkbox:checked');

                if (!checkboxes.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a object!'
                    });
                } else {
                    checkboxes.each(function () {
                        var $this = $(this);

                        $.ajax({
                            url: '/phpietadmin/targets/configure/addrule',
                            data: {
                                'iqn': $('#target_selector').find('option:selected').val(),
                                'type': $("input[name='type']:checked").val(),
                                'id': $this.closest('tr').find('.object_id').text()
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