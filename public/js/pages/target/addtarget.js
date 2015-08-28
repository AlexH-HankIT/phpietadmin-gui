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
                    } else if (e.which == 8 || e.which == 173 || e.which == 190) {
                        // keydown 8 is the deleted button
                        // keydown 190 is a dot
                        // keydown 173 is a minus
                        return true;
                        // prevent other special chars
                    } else if (e.which < 48 || (e.which > 57 && e.which < 65) || (e.which > 90 && e.which < 97) || e.which > 122) {
                        e.preventDefault();
                    }
                });
            });
        },
        focus_input: function () {
            // html5 autofocus does not work in firefox when loaded via ajax
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
            $(document).ready(function () {
                $(document).once('click', '#addtargetbutton', function () {
                    var iqninput = $('#iqninput');
                    var def = $('#defaultiqn').val();

                    if (iqninput.val() == "") {
                        iqninput.addClass("focusedInputerror");
                        return false;
                    } else {
                        var data = {
                            "name": def + iqninput.val()
                        };

                        $.ajax({
                            url: '/phpietadmin/targets/addtarget',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function(data) {
                                if (data['status'] == 'Success') {
                                    iqninput.focus();
                                    swal({
                                            title: 'Success',
                                            type: 'success',
                                            text: data['message']
                                        },
                                        function () {
                                            iqninput.val('');
                                        });
                                } else if (data['status'] == 'Error') {
                                    swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: data['message']
                                        },
                                        function () {
                                            iqninput.addClass("focusedInputerror");
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
                    }
                });
            });
        }
    };
});