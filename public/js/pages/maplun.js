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
            var target = $('#target');
            var def = $("#default");

            if(target.val() == def.val()) {
                sweetAlert("Error", "Please select a target!", "error");
            } else if ($('#logicalvolume').val() == def.val()) {
                sweetAlert("Error", "Please select a volume!", "error");
            } else {
                var data = {
                    "target": target.val(),
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