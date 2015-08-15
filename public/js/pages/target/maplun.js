define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        add_event_handler_maplunbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#maplunbutton', function(){
                    var targetselection =  $('#targetselection');
                    var iqn = targetselection.find("option:selected").val();
                    var lunselectiondefaultval =  $('#logicalvolumedefault').val();
                    var logicalvolume = $('#logicalvolume');

                if (logicalvolume.find("option:selected").val() == lunselectiondefaultval) {
                        swal("Error", "Please select a volume!", "error");
                    } else {
                        var data = {
                            "target": iqn,
                            "type": $('#type').find('option:selected').val(),
                            "mode": $('#mode').find('option:selected').val(),
                            "path": logicalvolume.val()
                        };

                        $.ajax({
                            url: '/phpietadmin/targets/configure/maplun',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function(data) {
                                if (data['code'] == 0) {
                                    var selectedvolume = logicalvolume.find("option:selected");
                                    selectedvolume.remove();

                                    if((logicalvolume.find('option').length) == 1) {
                                        $('#configuretargetbody').replaceWith('<div id="configuretargetbody">' +
                                        '<div class = "container">' +
                                        '<div class="alert alert-danger" role="alert"><h3 align="center">Error - No logical volumes available!</h3></div>' +
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
                            error: function(data) {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    }
                });
            });
        }
    };
});