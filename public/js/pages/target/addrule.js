define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        enable_filter_table_plugin: function() {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_addallowrulebutton: function() {
            $(function() {
                $(document).once('click', '#addallowrulebutton', function(){
                    var checkboxes = $(".objectcheckbox:checked");

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
                                    "iqn": $('#targetselection').find("option:selected").val(),
                                    "type":  $("input[name='type']:checked").val(),
                                    "id": $this.closest('tr').find('.objectid').text()
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function(data) {
                                    if (data['code'] == 0) {
                                        // uncheck all the checkbox
                                        $this.removeAttr('checked');

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
                        });
                    }
                });
            });
        }
    };
});