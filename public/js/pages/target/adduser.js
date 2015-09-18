define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function() {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_adduserbutton: function () {
            $(function() {
                $(document).once('click', '#adduserbutton', function () {
                    var selector_targetselection = $('#targetselection');
                    var iqn = selector_targetselection.find("option:selected").val();

                    if (!$(".addusercheckbox:checked").val()) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a user!'
                        });
                    } else {
                        // Select radio
                        var type = $("input[name='type']:checked").val();

                        // loop through checkboxes
                        $(".addusercheckbox:checked").each(function () {
                            var $this = $(this);
                            var id = $this.closest('tr').find('.userid').text();

                            $.ajax({
                                url: '/phpietadmin/targets/configure/adduser',
                                data: {
                                    "iqn": iqn,
                                    "type": type,
                                    "id": id
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function(data) {
                                    if (data['code'] == 0) {
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
                                    mylibs.loadconfiguretargetbody('/phpietadmin/targets/configure/adduser', iqn);
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