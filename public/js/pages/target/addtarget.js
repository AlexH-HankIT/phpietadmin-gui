define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    // Define vars
    var methods;

    return methods = {
        disable_special_chars: function () {
            var $iqninput = $('#iqninput');

            // Save default input for later restore
            var data = $iqninput.val();

            // Validates the iqn input field
            $iqninput.keydown(function (e) {
                // Prevent default data from being deleted
                var $this = $(this);
                var oldvalue = $this.val();

                setTimeout(function () {
                    if ($this.val().indexOf(data) !== 0) {
                        $this.val(oldvalue);
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

        },
        focus_input: function () {
            // html5 autofocus does not work in firefox when loaded via ajax
            $('#iqninput').focus();
        },
        remove_error: function () {
            var $iqninput = $('#iqninput');
            /* remove error if field is clicked */
            $iqninput.click(function () {
                $iqninput.removeClass('focusedInputerror');
            });
        },
        add_event_handler_addtargetbutton: function () {
            $('#addtargetbutton').once('click', function () {
                var $iqninput = $('#iqninput');
                var def = $('#defaultiqn').val();

                if ($iqninput.val() === '') {
                    $iqninput.addClass('focusedInputerror');
                    return false;
                } else {
                    $.ajax({
                        url: '/phpietadmin/targets/addtarget',
                        data: {
                            "name": def + $iqninput.val()
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                // focus does apparently not work in sweetalert callback
                                $iqninput.focus();
                                swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    },
                                    function () {
                                        $iqninput.val('');
                                    });
                            } else {
                                swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    },
                                    function () {
                                        $iqninput.addClass('focusedInputerror');
                                    });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            });
                        }
                    });
                }
            });
        }
    };
});