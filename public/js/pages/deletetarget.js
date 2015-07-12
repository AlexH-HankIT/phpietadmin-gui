define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_qtip_deletetargetbutton: function() {
            $(document).ready(function(){
                $('#deletetargetbutton').qtip({
                    content: {
                        text: 'Only unused targets without any connections or luns are displayed!'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
            });
        },
        add_event_handler_deletetargetbutton: function() {
            $(document).once('click', '#deletetargetbutton', function() {
                var targetdelete = $('#targetdelete');

                if(targetdelete.val() == $('#default').val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a target!'
                    });
                } else {
                    var data = {
                        "target": targetdelete.find('option:selected').val()
                    };

                    var request = mylibs.doajax("/phpietadmin/targets/deletetarget", data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == "Success") {
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
                }
            });
        }
    };
});