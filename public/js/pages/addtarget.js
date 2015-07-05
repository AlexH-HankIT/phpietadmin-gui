define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    // Define vars
    var methods;

    return methods = {
        disable_special_chars: function () {
            $(document).ready(function(){
                var iqninput = $('#iqninput');

                // Save default input for later restore
                var data = iqninput.val();

                // Validates the iqn input field
                iqninput.keydown(function (e) {
                    // Prevent default data from being deleted
                    var oldvalue = $(this).val();
                    var field = this;
                    setTimeout(function () {
                        if (field.value.indexOf(data) !== 0) {
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
            });
        },
        focus_input: function () {
            $(document).ready(function() {
                var iqninput = $('#iqninput');
                // Focus the iqninput when the site is loaded
                iqninput.focus();
            });
        },
        remove_error: function () {
            $(document).ready(function(){
                var iqninput = $('#iqninput');
                /* remove error if field is clicked */
                iqninput.click(function () {
                    iqninput.removeClass("focusedInputerror");
                });
            });
        },
        add_event_handler_addtargetbutton: function () {
            $(document).ready(function(){
                var iqninput = $('#iqninput');
                var data = iqninput.val();

                // Do ajax when button is clicked
                $(document).off('click', '#addtargetbutton');
                $(document).on('click', '#addtargetbutton', function () {

                    var def = $('#defaultiqn').val();

                    if (iqninput.val() == "") {
                        iqninput.addClass("focusedInputerror");
                        return false;
                    } else {
                        var data = {
                            "name": def + iqninput.val()
                        };

                        request = mylibs.doajax('/phpietadmin/targets/addtarget', data);

                        request.done(function () {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == 'Success') {
                                    swal({
                                            title: 'Success',
                                            type: 'success'
                                        },
                                        function () {
                                            //iqninput.val('');
                                            window.location.reload(true);
                                        });
                                } else {
                                    swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: request.responseText
                                        },
                                        function () {
                                            window.location.reload(true);
                                            //iqninput.addClass("focusedInputerror");
                                        });
                                }
                            }
                        })
                    }
                });
            });
        }
    };
});