define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    return {
        add_event_handler_adddisuserbutton: function() {
            $(function() {
                $(document).once('click', '#adddisuserbutton', function() {
                    var type = $("input[name='type']:checked").val();
                    var checkbox = $(".adddisusercheckbox:checked");

                    // check if even one checkbox is checked
                    if (!checkbox.val()) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a user!'
                        });
                    } else {
                        checkbox.each(function () {
                            var $this = $(this);
                            $.ajax({
                                url: require.toUrl('../targets/adddisuser'),
                                beforeSend: mylibs.checkAjaxRunning(),
                                data: {
                                    "type": type,
                                    "id": $this.closest('tr').find('.userid').text()
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function(data) {
                                    if (data['code'] == 0) {
                                        swal({
                                            title: 'Success',
                                            type: 'success',
                                            text: data['message']
                                        });
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: data['message']
                                        });
                                    }
                                },
                                error: function() {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'Something went wrong while submitting!'
                                    });
                                }
                            });
                            $this.prop('checked', false);
                        });
                    }
                });
            });
        },
        enable_filter_table_plugin: function() {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        }
    };
});