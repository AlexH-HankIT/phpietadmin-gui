define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var Methods;
    return Methods = {
        add_event_handler_maplunbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#maplunbutton', function(){
                    var targetselection =  $('#targetselection');
                    var iqn = targetselection.find("option:selected").val();
                    var defaultvalue = targetselection.find('#default').val();
                    var lunselectiondefaultval =  $('#logicalvolumedefault').val();

                    if(iqn == defaultvalue) {
                        swal("Error", "Please select a target!", "error");
                    } else if ($('#logicalvolume').find("option:selected").val() == lunselectiondefaultval) {
                        swal("Error", "Please select a volume!", "error");
                    } else {
                        var data = {
                            "target": iqn,
                            "type": $('#type').find('option:selected').val(),
                            "mode": $('#mode').find('option:selected').val(),
                            "path": $('#logicalvolume').val()
                        };

                        var request = mylibs.doajax('/phpietadmin/targets/maplun', data);

                        request.done(function() {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == 'Success') {
                                    var logicalvolume = $('#logicalvolume');
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
                                        type: 'success'
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: request.responseText
                                    });
                                }
                                mylibs.loadconfiguretargetbody('/phpietadmin/targets/maplun');
                            }
                        });
                    }
                });
            });
        }
    };
        // shows/hides the manual input
        /*maplun.hide();
        $("#check").change(function() {
            if(maplun.is(':hidden')) {
                maplun.attr("required");
                maplun.show();
                autoselection.hide();
            } else {
                autoselection.show();
                maplun.hide();
            }
        });*/
});