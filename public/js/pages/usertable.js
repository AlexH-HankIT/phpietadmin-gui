define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('mouseover', '.passwordfield', function() {
            $(this).find('.passwordfieldplaceholder').hide();
            $(this).find('.password').show();
        });

        $(document).on('mouseout', '.passwordfield', function() {
            $(this).find('.passwordfieldplaceholder').show();
            $(this).find('.password').hide();
        });

        $(document).on('click', '#adduserrowbutton', function() {
            $('#adduserrowbutton').hide();
        });

        $(document).on('click', '.deleteuserrow', function() {
            var thisrow = $(this).closest('tr');

            if (thisrow.hasClass('newrow')) {
                $('.newrow').remove();
            } else {
                swal({
                        title: "Are you sure?",
                        text: "The user won't be deleted from the iet config file!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function () {
                        // Delete row from database
                        // Ajax id to delete url
                        var data = {
                            "id": thisrow.find('.id').text()
                        };

                        request = mylibs.doajax('/phpietadmin/ietusers/deleteuserfromdb', data);

                        request.done(function () {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == "Success") {
                                    swal({
                                            title: 'Success',
                                            type: 'success'
                                        },
                                        function () {
                                            thisrow.remove();
                                        });
                                } else {
                                    swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: request.responseText
                                        });
                                }
                            }
                        });

                    });
            }
            $('#adduserrowbutton').show();
        });

        $(document).on('click', '#adduserrowbutton', function() {
            $('#addusertablebody').append(
                '<tr class="newrow">' +
                '<td>' +
                '<input class="username" type="text" placeholder="Username">' +
                '<span class="label label-success bestaetigung">Success</span>' +
                '</td>' +
                '<td>' +
                '<input class="password" type="text" placeholder="Password"><a href="#"> <span id="generatepw" class="glyphicon glyphicon-hand-left glyphicon-20" aria-hidden="true"></span></a>' +
                '<span class="label label-success bestaetigung">Success</span>' +
                '</td>' +
                '<td><a href="#" class="deleteuserrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>' +
                '<td><a href="#" class="saveuserrow"><span class="glyphicon glyphicon-save glyphicon-20" aria-hidden="true"></span></a></td>' +
                '</tr>'
            );

            $('#generatepw').qtip({
                content: {
                    text: 'An eight char password containing upper and lower case letters and digits will be generated'
                },
                style: {
                    classes: 'qtip-youtube'
                }
            });

            $(document).on('click', '#generatepw', function() {
                var selpassword = $('.password');
                var password = mylibs.generatePassword();
                selpassword.val(password);
            });

            $(document).on('focus', '.password', function() {
                var password = $(".password");
                if (password.hasClass("focusedInputerror")) {
                    password.removeClass("focusedInputerror");
                }
            });

            $(document).on('focus', '.username', function() {
                var username = $(".username");
                if (username.hasClass("focusedInputerror")) {
                    username.removeClass("focusedInputerror");
                }
            });

            $(document).on('click', '.saveuserrow', function() {
                // Check if username and password isset
                var selpassword = $('.password');
                var username =  $(".username");
                var usernameval = username.val();

                if (usernameval == '') {
                    username.addClass("focusedInputerror");
                    username.next('.bestaetigung').addClass("label-danger");
                    username.next('.bestaetigung').text("Required");
                    username.next('.bestaetigung').show(500);
                    username.next('.bestaetigung').delay(2000).hide(0);
                } else if(selpassword.val() == '') {
                    selpassword.addClass("focusedInputerror");
                    selpassword.next('.bestaetigung').addClass("label-danger");
                    selpassword.next('.bestaetigung').text("Required");
                    selpassword.next('.bestaetigung').show(500);
                    selpassword.next('.bestaetigung').delay(2000).hide(0);
                } else {
                    // Check if username already exists
                    var data = {
                        "username": usernameval
                    };

                    request = mylibs.doajax('/phpietadmin/ietusers/check_username_already_in_use', data);

                    request.done(function () {
                        if (request.readyState == 4 && request.status == 200) {
                            if (request.responseText == "true") {
                                // Display bad message here because user already exists
                                username.addClass("focusedInputerror");
                                username.next('.bestaetigung').addClass("label-danger");
                                username.next('.bestaetigung').text("In use!");
                                username.next('.bestaetigung').show(500);
                                username.next('.bestaetigung').delay(2000).hide(0);
                            } else {
                                // Ajax data to server and reload page
                                var data = {
                                    "username": usernameval,
                                    "password": $('.password').val()
                                };

                                request = mylibs.doajax('/phpietadmin/ietusers/addusertodb', data);

                                request.done(function () {
                                    if (request.readyState == 4 && request.status == 200) {
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    });
});

