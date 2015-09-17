define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        add_event_handler_deletelunbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#deletelunbutton', function() {
                    var deletelunlunselection = $('#deletelunlunselection');

                    var data = {
                        "iqn": $('#targetselection').find("option:selected").val(),
                        "path": deletelunlunselection.find('option:selected').attr('name')
                    };

                    $.ajax({
                        url: '/phpietadmin/targets/configure/deletelun',
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        success: function(data) {
                            if (data['code'] == 0) {
                                // remove selected element
                                deletelunlunselection.find('option:selected').remove();

                                if((deletelunlunselection.has('option').length) == 0) {
                                    $('#configure_target_body').replaceWith('<div id="configure_target_body">' +
                                    '<div class = "container">' +
                                    '<div class="alert alert-warning" role="alert"><h3 align="center">Error - No lun available!</h3></div>' +
                                    '</div>' +
                                    '</div>')
                                }

                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
                                });
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
            });
        }
    };
});