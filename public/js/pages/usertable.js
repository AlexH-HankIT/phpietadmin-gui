define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_passwordfield: function () {
            // show/hide password on mouseover

            var $passwordfield = $('.passwordfield');
            var $placeholder = $(this).find('.passwordfieldplaceholder');

            $passwordfield.once('mouseover', function () {
                $placeholder.hide().find('.password').show();
            });

            $passwordfield.once('mouseout', function () {
                $placeholder.show().find('.password').hide();
            });
        },
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_deleteuserrow: function () {
            $('.deleteuserrow').once('click', function () {
                var $this_row = $(this).closest('tr');

                if ($this_row.hasClass('newrow')) {
                    $('.newrow').remove();
                } else {
                    swal({
                            title: 'Are you sure?',
                            text: 'The user won\'t be deleted from the iet config file!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes, delete it!',
                            closeOnConfirm: false
                        },
                        function () {
                            $.ajax({
                                url: '/phpietadmin/ietusers/delete_from_db',
                                data: {
                                    "username": $this_row.find('.username').text()
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] === 0) {
                                        swal({
                                            title: 'Success',
                                            type: 'success',
                                            text: data['message']
                                        }, function () {
                                            return mylibs.load_workspace('/phpietadmin/ietusers');
                                        });
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: data['message']
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
                        });
                }
                $('#adduserrowbutton').show();
            });
        },
        add_event_handler_adduserrowbutton: function () {
            $('#adduserrowbutton').once('click', function () {
                // hide add button
                $(this).hide();

                // add click event for password generator
                $('#addusertablebody').once('click', '.generate_pw', function () {
                    $('.password').val(mylibs.generatePassword());
                });

                $('.generate_pw').qtip({
                    content: {
                        text: 'An sixteen char password containing upper and lower case letters and digits will be generated.' +
                        'Note: Passwords for discovery users can be at most 12 chars long!'
                    },
                    show: 'mouseover',
                    hide: 'mouseout',
                    style: {
                        classes: 'qtip-youtube'
                    }
                });

                $('.password').once('focus', function () {
                    var $selpassword = $('.password');
                    if ($selpassword.hasClass('focusedInputerror')) {
                        $selpassword.removeClass('focusedInputerror');
                    }
                });

                $('.username').once('focus', function () {
                    var $selusername = $('.username');
                    if ($selusername.hasClass('focusedInputerror')) {
                        $selusername.removeClass('focusedInputerror');
                    }
                });

                $('#template').clone().prependTo('#addusertablebody').removeAttr('id hidden').addClass('newrow');

                $('.saveuserrow').once('click', function () {
                    var $this = $(this);

                    // Check if username and password isset
                    var $selpassword = $this.closest('tr').find('.password');
                    var selpasswordval = $selpassword.val();
                    var $selpasswordconfirm = $selpassword.next('.bestaetigung');

                    var $selusername = $this.closest('tr').find('.username');
                    var selusernameval = $selusername.val();
                    var $selusernameconfirm = $selusername.next('.bestaetigung');

                    if (selusernameval === '') {
                        $selusername.addClass('focusedInputerror');
                        $selusernameconfirm.addClass('label-danger').text('Required').show(500).delay(2000).hide(0);
                    } else if (selpasswordval === '') {
                        $selpassword.addClass("focusedInputerror");
                        $selpasswordconfirm.addClass('label-danger').text('Required').show(500).delay(2000).hide(0);
                    } else if (selpasswordval.length < 12) {
                        $selpassword.addClass("focusedInputerror");
                        $selpasswordconfirm.addClass('label-danger').text('Min 12 chars').show(500).delay(2000).hide(0);
                    } else {
                        $.ajax({
                            url: '/phpietadmin/ietusers/add_to_db',
                            data: {
                                "username": selusernameval,
                                "password": selpasswordval
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    }, function () {
                                        return mylibs.load_workspace('/phpietadmin/ietusers');
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
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
            });
        }
    };
});