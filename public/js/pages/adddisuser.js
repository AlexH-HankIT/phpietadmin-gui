define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;

    return Methods = {
        add_event_handler_adddisuserbutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#adddisuserbutton');
                $(document).on('click', '#adddisuserbutton', function() {
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
                            var data = {
                                "type": type,
                                "id": $(this).closest('tr').find('.userid').text()
                            };

                            request = mylibs.doajax("/phpietadmin/permission/adddisuser", data);

                            request.done(function () {
                                if (request.readyState == 4 && request.status == 200) {
                                    if (request.responseText == true) {
                                        swal({
                                                title: 'Success',
                                                type: 'success'
                                            },
                                            function () {
                                                location.reload();
                                            });
                                    } else {
                                        swal({
                                                title: 'Error',
                                                type: 'error',
                                                text: request.responseText
                                            },
                                            function () {
                                                location.reload();
                                            });
                                    }
                                }
                            });

                        });
                    }
                });
            });
        }
    };
});