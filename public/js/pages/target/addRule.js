define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});

        },
        add_event_handler_addallowrulebutton: function () {
            var $masterCheckbox = $('#masterCheckbox');
            mylibs.select_all_checkbox($masterCheckbox);

            $('#addAllowRuleButton').once('click', function () {
                var checkboxes = $('.objectCheckbox:checked'),
                    $button = $(this);

                if (!checkboxes.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a object!'
                    });
                } else {
                    checkboxes.each(function () {
                        var $this = $(this);
                        $button.button('loading');
                        $.ajax({
                            url: require.toUrl('../targets/configure/' + $('#targetSelect').find('option:selected').val() + '/addrule'),
                            beforeSend: mylibs.checkAjaxRunning(),
                            data: {
                                'type': $("input[name='type']:checked").val(),
                                'id': $this.closest('tr').find('.objectId').text()
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
                                    $masterCheckbox.removeAttr('checked');
                                    $this.removeAttr('checked');
                                    $button.button('reset');
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    }, function() {
                                        $button.button('reset');
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
                                });
                            }
                        });
                    });
                }
            });
        }
    };
});