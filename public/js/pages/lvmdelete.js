define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_qtip_logicalvolumedeletebutton: function() {
            $(document).ready(function(){
                $('#logicalvolumedeletebutton').qtip({
                    content: {
                        text: 'Only unused volumes are displayed!'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
            });
        },
        add_event_handler_logicalvolumedeletebutton: function() {
            $(document).ready(function(){
                var sel = $('#logicalvolumedeleteselection');

                $(document).once('click', '#logicalvolumedeletebutton', function(){
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

                        var request = mylibs.doajax("/phpietadmin/lvm/delete", data);

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
        }
    };
});