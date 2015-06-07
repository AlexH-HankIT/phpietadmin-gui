define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('click', '#deletetargetbutton', function() {
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

                request = mylibs.doajax("/phpietadmin/targets/deletetarget", data);

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
    });
});