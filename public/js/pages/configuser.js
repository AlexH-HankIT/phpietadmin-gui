define(['jquery', 'mylibs', 'sweetalert', 'sha256'], function($, mylibs, swal, sha256) {
    var methods;

    return methods = {
        add_event_handler_editpassword: function() {
            $(document).ready(function(){
                var password1 = $('.password1');
                var password2 = $('.password2');
                var editpassword = $('#editpassword');
                var savepasswordatag = $('#savepassworda');

                $(document).once('click', '#editpassword', function() {
                    if (password1.prop('disabled') == true && password2.prop('disabled') == true) {
                        password1.prop('disabled', false);
                        password2.prop('disabled', false);
                        password1.val('');
                        password2.val('');
                        password1.focus();
                        editpassword.removeClass('glyphicon-pencil');
                        editpassword.addClass('glyphicon-remove');
                        savepasswordatag.show();
                    } else {
                        password1.prop('disabled', true);
                        password2.prop('disabled', true);
                        password1.val('           ');
                        password2.val('           ');

                        editpassword.removeClass('glyphicon-remove');
                        editpassword.addClass('glyphicon-pencil');
                        savepasswordatag.hide();
                    }
                });
            });
        },
        add_event_handler_savepassword: function() {
            $(document).ready(function(){
                var password1 = $('.password1');
                var password2 = $('.password2');

                $(document).once('click', '#savepassword', function() {
                    $(document).once('focus', '.password1', function () {
                        if (password1.hasClass("focusedInputerror")) {
                            password1.removeClass("focusedInputerror");
                        }
                    });

                    $(document).once('focus', '.password2', function () {
                        if (password2.hasClass("focusedInputerror")) {
                            password2.removeClass("focusedInputerror");
                        }
                    });

                    if (password1.val() == '') {
                        password1.addClass("focusedInputerror");
                        password1.next('.bestaetigung').addClass("label-danger");
                        password1.next('.bestaetigung').text("Required");
                        password1.next('.bestaetigung').show(500);
                        password1.next('.bestaetigung').delay(2000).hide(0);
                    } else if (password2.val() == '') {
                        password2.addClass("focusedInputerror");
                        password2.next('.bestaetigung').addClass("label-danger");
                        password2.next('.bestaetigung').text("Required");
                        password2.next('.bestaetigung').show(500);
                        password2.next('.bestaetigung').delay(2000).hide(0);
                    } else {
                        // Check if password1 and password2 matches
                        if (password1.val() == password2.val()) {
                            // Create sha256 hash from password
                            var pwhash = sha256.hash(password1.val());

                            // Ajax password to server
                            var data = {
                                "pwhash": pwhash
                            };

                            var request = mylibs.doajax('/phpietadmin/config/editloginuser', data);

                            request.done(function () {
                                if (request.readyState == 4 && request.status == 200) {
                                    if (request.responseText == "Success") {
                                        // In case of success, reload the page for relogin
                                        location.reload();
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: request.responseText
                                        });
                                    }
                                }
                            });
                        } else {
                            // Display bad message here
                            password1.addClass("focusedInputerror");
                            password1.next('.bestaetigung').addClass("label-danger");
                            password1.next('.bestaetigung').text("Doesn't match");
                            password1.next('.bestaetigung').show(500);
                            password1.next('.bestaetigung').delay(2000).hide(0);

                            password2.addClass("focusedInputerror");
                            password2.next('.bestaetigung').addClass("label-danger");
                            password2.next('.bestaetigung').text("Doesn't match");
                            password2.next('.bestaetigung').show(500);
                            password2.next('.bestaetigung').delay(2000).hide(0);
                        }
                    }
                });
            });
        }
    };
});