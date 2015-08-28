define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        enable_filter_table_plugin: function() {
            $(document).ready(function(){
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_addallowrulebutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#addallowrulebutton', function(){
                    var selector_targetselection = $('#targetselection');
                    var iqn = selector_targetselection.find("option:selected").val();
                    var defaultvalue = selector_targetselection.find('#default').val();

                    if (iqn == defaultvalue) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a iqn!'
                        });
                    } else if (!$(".objectcheckbox:checked").val()) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a object!'
                        });
                    } else {
                        var type = $("input[name='type']:checked").val();

                        $(".objectcheckbox:checked").each(function () {
                            var $this = $(this);
                            var id = $this.closest('tr').find('.objectid').text();

                            $.ajax({
                                url: '/phpietadmin/targets/configure/addrule',
                                data: {
                                    "iqn": iqn,
                                    "type": type,
                                    "id": id
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

                                    return mylibs.load_configure_target_body(iqn, '/phpietadmin/targets/configure/addrule');
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