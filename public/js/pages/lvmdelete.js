define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $('#logicalvolumedeletebutton').qtip({
            content: {
                text: 'Only unused volumes are displayed!'
            },
            style: {
                classes: 'qtip-youtube'
            }
        });

        var sel = $('#logicalvolumedeleteselection');

        $(document).on('click', '#logicalvolumedeletebutton', function(){
            if(sel.find('option:selected').val() == $("#default").val()) {
                swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a volume!'
                    });
            } else {
                var data = {
                    "target": sel.find('option:selected').val()
                };

                request = mylibs.doajax("/phpietadmin/lvm/delete", data);

                request.done(function() {
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
                    }
                });
            }
        });
    });
});