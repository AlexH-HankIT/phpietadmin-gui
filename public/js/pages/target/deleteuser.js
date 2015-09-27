define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function() {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_deleteuserbutton: function() {
            $(function() {
                var seltargetselection = $('#target_selector');

                $(document).once('click', '#deleteuserbutton', function(){
                    var iqn = seltargetselection.find("option:selected").val();
                    var defaultvalue = seltargetselection.find('#default').val();

                    if (iqn !== defaultvalue) {
                        var userdeletecheckbox = $(".userdeletecheckbox:checked");
                        if (!userdeletecheckbox.val()) {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Please select a user'
                            });
                        } else {
                            userdeletecheckbox.each(function () {
                                var $this = $(this);

                                $.ajax({
                                    url: '/phpietadmin/targets/configure/deleteuser',
                                    data: {
                                        "iqn": seltargetselection.find('option:selected').val(),
                                        "id": $(this).closest('tr').find('.id').text(),
                                        "type": $(this).closest('tr').find('.type').text()
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
                                            $this.closest('tr').remove();

                                            if ($('#delete_user_table_body').length == 1) {
                                                $('#delete_user_panel').replaceWith('<div class="alert alert-warning" role="alert"><h3 align="center">Error - No users set for this target!</h3></div>');
                                            }
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
                    }
                });
            });
        }
    };
});