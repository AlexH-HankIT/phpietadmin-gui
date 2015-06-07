define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        // Validates the iqn input field
        // Save default input for later restore
        var iqninput = $("#iqninput");
        var data = iqninput.val();

        iqninput.keydown( function(e) {
            // Prevent default data from being deleted
            var oldvalue=$(this).val();
            var field=this;
            setTimeout(function () {
                if(field.value.indexOf(data) !== 0) {
                    $(field).val(oldvalue);
                }
            }, 1);

            // disable shift button
            if (e.shiftKey) {
                e.preventDefault();
            } else if (e.which == 8) {
                // keydown 8 is the deleted button
                return true;
                // prevent other special chars
            } else if (e.which < 48 || (e.which > 57 && e.which < 65) || (e.which > 90 && e.which < 97) || e.which > 122) {
                e.preventDefault();
            }
        });

        // Focus the iqninput when the site is loaded
        iqninput.focus();
        var $thisVal = iqninput.val();
        iqninput.val('').val($thisVal);

        /* remove error if field is clicked */
        iqninput.click(function () {
            iqninput.removeClass("focusedInputerror");
        });

        // Do ajax when button is clicked
        $(document).on('click', '#addtargetbutton', function(){

            var def = $('#defaultiqn').val();

            if (iqninput.val() == "") {
                iqninput.addClass("focusedInputerror");
                return false;
            } else {
                var data = {
                    "name": def + iqninput.val()
                };

                request = mylibs.doajax("/phpietadmin/targets/addtarget", data);

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
                })
            }
        });
    });
});
