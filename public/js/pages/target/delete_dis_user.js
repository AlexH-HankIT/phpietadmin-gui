define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function() {
            $(document).ready(function(){
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_deletedisuserbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#delete_dis_user_button', function() {
                    var checkbox = $(".delete_dis_user_checkbox:checked");

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
                                url: '/phpietadmin/targets/deletedisuser',
                                data: {
                                    "id": $(this).closest('tr').find('.delete_dis_user_id').text(),
                                    "type": $(this).closest('tr').find('.delete_dis_user_type').text()
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function(data) {
                                    if (data['code'] == 0) {
                                        swal({
                                            title: 'Success',
                                            type: 'success',
                                            text: data['message']
                                            },
                                            function () {
                                                location.reload();
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
        }
    };
});