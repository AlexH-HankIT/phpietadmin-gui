define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        add_event_handler_deletelunbutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#deletelunbutton');
                $(document).on('click', '#deletelunbutton', function() {
                    var deletelunlunselection = $('#deletelunlunselection');
                    var iqn = $('#targetselection').find("option:selected").val();

                    var data = {
                        "iqn": iqn,
                        "lun": deletelunlunselection.find('option:selected').val(),
                        "path": deletelunlunselection.find('option:selected').attr('name')
                    };

                    var request = mylibs.doajax("/phpietadmin/targets/deletelun", data);

                    request.done(function () {
                        if (request.readyState == 4 && request.status == 200) {
                            if (request.responseText == "Success") {
                                swal({
                                        title: 'Success',
                                        type: 'success'
                                    },
                                    function () {
                                        // remove selected element
                                        deletelunlunselection.find('option:selected').remove();

                                        if((deletelunlunselection.has('option').length) == 0) {
                                            $('#configuretargetbody').replaceWith('<div id="configuretargetbody">' +
                                            '<div class = "container">' +
                                            '<div class="alert alert-danger" role="alert"><h3 align="center">Error - No luns available</h3></div>' +
                                            '</div>' +
                                            '</div>')
                                        }
                                    });
                            } else {
                                swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: request.responseText
                                    });
                            }

                        }
                    })
                });
            });
        }
    };
});