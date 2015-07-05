define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        add_event_handler_addallowrulebutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#addallowrulebutton');
                $(document).on('click', '#addallowrulebutton', function(){
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

                            var data = {
                                "iqn": iqn,
                                "type": type,
                                "id": id
                            };

                            var request = mylibs.doajax("/phpietadmin/permission/addrule", data);

                            request.done(function() {
                                if (request.readyState == 4 && request.status == 200) {
                                    if (request.responseText == "Success") {
                                        // uncheck all the checkbox
                                        $this.removeAttr('checked');

                                        swal({
                                            title: 'Success',
                                            type: 'success'
                                        });
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: request.responseText
                                        });
                                    }
                                    mylibs.loadconfiguretargetbody('permission/addrule', iqn);
                                }
                            });
                        });
                    }
                });
            });
        }
    };
});