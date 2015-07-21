define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_passwordfield1: function() {
            $(document).ready(function(){
                $(document).once('mouseover', '.passwordfield', function() {
                    $(this).find('.passwordfieldplaceholder').hide();
                    $(this).find('.password').show();
                });
            });
        },
        enable_filter_table_plugin: function() {
            $(document).ready(function(){
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});
            });
        },
        add_event_handler_passwordfield2: function() {
            $(document).ready(function(){
                $(document).once('mouseout', '.passwordfield', function() {
                    $(this).find('.passwordfieldplaceholder').show();
                    $(this).find('.password').hide();
                });
            });
        },
        add_event_handler_deleteuserrow: function() {
            $(document).ready(function(){
                $(document).once('click', '.deleteuserrow', function() {
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

                                var request = mylibs.doajax('/phpietadmin/ietusers/deleteuserfromdb', data);

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
            });
        },
        add_event_handler_adduserrowbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#adduserrowbutton', function() {
                    $('#adduserrowbutton').hide();

                    $('#template').clone().prependTo('#addusertablebody').removeAttr('id hidden').addClass("newrow");

                    $('#generatepw').qtip({
                        content: {
                            text: 'An sixteen char password containing upper and lower case letters and digits will be generated.'
                        },
                        style: {
                            classes: 'qtip-youtube'
                        }
                    });

                    $(document).once('click', '#generatepw', function() {
                        var selpassword = $('.password');
                        var password = mylibs.generatePassword();
                        selpassword.val(password);
                    });

                    $(document).once('focus', '.password', function() {
                        var selpassword = $(".password");
                        if (selpassword.hasClass("focusedInputerror")) {
                            selpassword.removeClass("focusedInputerror");
                        }
                    });

                    $(document).once('focus', '.username', function() {
                        var selusername = $(".username");
                        if (selusername.hasClass("focusedInputerror")) {
                            selusername.removeClass("focusedInputerror");
                        }
                    });

                    $(document).once('click', '.saveuserrow', function() {
                        // Check if username and password isset
                        var selpassword = $(this).closest("tr").find('.password');
                        var selpasswordval = selpassword.val();
                        var selpasswordconfirm = selpassword.next('.bestaetigung');

                        var selusername =  $(this).closest("tr").find('.username');
                        //var passwordcell = $(this).closest('tr').find('.passwordcell');
                        //var usernamecell = $(this).closest('tr').find('.usernamecell');
                        var selusernameval = selusername.val();
                        var selusernameconfirm = selusername.next('.bestaetigung');


                        if (selusernameval == '') {
                            selusername.addClass("focusedInputerror");
                            selusernameconfirm.addClass("label-danger");
                            selusernameconfirm.text("Required");
                            selusernameconfirm.show(500);
                            selusernameconfirm.delay(2000).hide(0);
                        } else if (selpasswordval == '') {
                            selpassword.addClass("focusedInputerror");
                            selpasswordconfirm.addClass("label-danger");
                            selpasswordconfirm.text("Required");
                            selpasswordconfirm.show(500);
                            selpasswordconfirm.delay(2000).hide(0);
                        } else if (selpasswordval.length < 12) {
                            selpassword.addClass("focusedInputerror");
                            selpasswordconfirm.addClass("label-danger");
                            selpasswordconfirm.text("Min 12 chars");
                            selpasswordconfirm.show(500);
                            selpasswordconfirm.delay(2000).hide(0);
                        } else {
                            // Check if username already exists
                            var data = {
                                "username": selusernameval
                            };

                            var request = mylibs.doajax('/phpietadmin/ietusers/check_username_already_in_use', data);

                            request.done(function () {
                                if (request.readyState == 4 && request.status == 200) {
                                    if (request.responseText == "true") {
                                        // Display bad message here because user already exists
                                        selusername.addClass("focusedInputerror");
                                        selusernameconfirm.addClass("label-danger");
                                        selusernameconfirm.text("In use!");
                                        selusernameconfirm.show(500);
                                        selusernameconfirm.delay(2000).hide(0);
                                    } else {
                                        // Ajax data to server and reload page
                                        var data = {
                                            "username": selusernameval,
                                            "password": selpasswordval
                                        };

                                        request = mylibs.doajax('/phpietadmin/ietusers/addusertodb', data);

                                        request.done(function () {
                                            if (request.readyState == 4 && request.status == 200) {
                                                //passwordcell.html(selpasswordval);
                                                //usernamecell.html(selusernameval);

                                                window.location.reload();
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
            });
        }
    };
});