define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_deletedisuserbutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#deletedisuserbutton');
                $(document).on('click', '#deletedisuserbutton', function() {
                    var checkbox = $(".deletedisusercheckbox:checked");

                    if (!checkbox.val()) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please select a user!'
                        });
                    } else {
                        checkbox.each(function () {
                            var data = {
                                "username": $(this).closest('tr').find('.deletedisusername').text(),
                                "type": $(this).closest('tr').find('.deletedisusertype').text()
                            };

                            request = mylibs.doajax("/phpietadmin/permission/deletedisuser", data);

                            request.done(function() {
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