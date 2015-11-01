define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_editpassword: function () {
            var password1 = $('.password1'),
                password2 = $('.password2'),
                editpassword = $('#editpassword'),
                savepasswordatag = $('#savepassworda');

            $('#workspace').once('click', '#editpassword', function () {
                if (password1.prop('disabled') == true && password2.prop('disabled') == true) {
                    password1.prop('disabled', false).val('').focus();
                    password2.prop('disabled', false).val('');
                    editpassword.removeClass('glyphicon-pencil').addClass('glyphicon-remove');
                    savepasswordatag.show();
                } else {
                    password1.prop('disabled', true).val('           ');
                    password2.prop('disabled', true).val('           ');
                    editpassword.removeClass('glyphicon-remove').addClass('glyphicon-pencil');
                    savepasswordatag.hide();
                }
            });
        },
        add_event_handler_savepassword: function () {
            var password1 = $('.password1'),
                password2 = $('.password2'),
                workspace = $('#workspace');

            workspace.once('click', '#savepassword', function () {
                workspace.once('focus', '.password1', function () {
                    password1.removeClass("focusedInputerror");
                });

                workspace.once('focus', '.password2', function () {
                    password2.removeClass("focusedInputerror");
                });

                if (password1.val() === '') {
                    password1.addClass("focusedInputerror").addClass("label-danger").text("Required").show(500).delay(2000).hide(0);
                } else if (password2.val() == '') {
                    password2.addClass("focusedInputerror").addClass("label-danger").text("Required").show(500).delay(2000).hide(0);
                } else {
                    // Check if password1 and password2 matches
                    if (password1.val() === password2.val()) {
                        $.ajax({
                            url: '/phpietadmin/config/user/change',
                            data: {
                                "username": "",
                                "row": "password",
                                "value": password1.val()
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                // fetch page update via ajax
                            },
                            error: function () {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    } else {
                        // Display bad message here
                        password1.addClass("focusedInputerror").addClass("label-danger").text("Doesn't match").show(500).delay(2000).hide(0);
                        password2.addClass("focusedInputerror").addClass("label-danger").text("Doesn't match").show(500).delay(2000).hide(0);
                    }
                }
            });
        }
    };
});