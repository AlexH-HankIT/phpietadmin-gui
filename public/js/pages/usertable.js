define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_passwordfield: function () {
            // show/hide password on mouseover
            $('.passwordfield').once('mouseout mouseover', function () {
                var $this = $(this);
                $this.find('.passwordfieldplaceholder').toggle();
                $this.find('.password').toggle();
            });
        },
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_deleteuserrow: function () {
            $('#workspace').once('click', '.deleteuserrow', function () {
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
                                        });
                                        return mylibs.load_workspace('/phpietadmin/ietusers');
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
            var $workspace = $('#workspace');

            $('#adduserrowbutton').once('click', function () {
                // hide add button
                $(this).hide();

                // add click event for password generator
                $workspace.once('click', '.generate_pw', function () {
                    $('.password').val(mylibs.generatePassword());
                });

                $workspace.once('focus', '.password', function () {
                    $('.password').removeClass('focusedInputerror');
                });

                $workspace.once('focus', '.username', function () {
                    $('.username').removeClass('focusedInputerror');
                });

                $('#template').clone().prependTo('#addusertablebody').removeAttr('id hidden').addClass('newrow');

                $('.saveuserrow').once('click', function () {
                    // Check if username and password isset
                    var $this = $(this),
                        $selpassword = $this.closest('tr').find('.password'),
                        selpasswordval = $selpassword.val(),
                        $selpasswordconfirm = $selpassword.next('.bestaetigung'),
                        $selusername = $this.closest('tr').find('.username'),
                        selusernameval = $selusername.val(),
                        $selusernameconfirm = $selusername.next('.bestaetigung');

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
                                    return mylibs.load_workspace('/phpietadmin/ietusers');
                                } else {
                                    $selusername.addClass('focusedInputerror');
                                    $selusernameconfirm.addClass('label-danger').text(data['message']).show(500).delay(2000).hide(0);
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