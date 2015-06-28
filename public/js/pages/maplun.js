define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
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

        $(document).on('click', '#maplunbutton', function(){
            var targetselection =  $('#targetselection');
            var iqn = targetselection.find("option:selected").val();
            var defaultvalue = targetselection.find('#default').val();
            var lunselectiondefaultval =  $('#logicalvolumedefault').val();

            if(iqn == defaultvalue) {
                sweetAlert("Error", "Please select a target!", "error");
            } else if ($('#logicalvolume').find("option:selected").val() == lunselectiondefaultval) {
                sweetAlert("Error", "Please select a volume!", "error");
            } else {
                var data = {
                    "target": iqn,
                    "type": $('#type').find('option:selected').val(),
                    "mode": $('#mode').find('option:selected').val(),
                    "path": $('#logicalvolume').val()
                };

                request = mylibs.doajax("/phpietadmin/targets/maplun", data);

                request.done(function() {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == 'Success') {
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
});