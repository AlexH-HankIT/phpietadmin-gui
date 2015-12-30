define(['jquery', 'sweetalert', 'mylibs'], function ($, swal, mylibs) {
    return {
        editPasswordModel: function() {
            var $editPasswordModal = $('#editPasswordModal'),
                $passwordInput = $('.passwordInput'),
                $inputPassword = $('#inputPassword');

            $editPasswordModal.once('shown.bs.modal', function () {
                $inputPassword.focus();
            });

            // Remove error, when input field is filled
            $passwordInput.once('click', function() {
                $(this).parent('div').removeClass('has-error');
            });

            // insert username into loaded modal
            $('.editPasswordSpan').once('click', function() {
                $('#savedUsername').val($(this).closest('tr').find('.username').text());
            });

            $('#savePasswordButton').once('click', function() {
                var inputPasswordVal = $inputPassword.val(),
                    inputPasswordRepeatVal = $('#inputPasswordRepeat').val(),
                    $passwordInputParentDiv = $passwordInput.parent('div');

                if ($passwordInput.val() === '' || inputPasswordVal !== inputPasswordRepeatVal) {
                    $passwordInputParentDiv.addClass('has-error');
                } else {
                    $passwordInputParentDiv.removeClass('has-error').addClass('has-success');
                }

                // Only close modal on success
                if ($passwordInputParentDiv.hasClass('has-success')) {
                    $.ajax({
                        url: require.toUrl('../config/user/change'),
                        data: {
                            "username": $('#savedUsername').val(),
                            "value": inputPasswordVal
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                // Wait a bit to inform the user of the success
                                setTimeout(function() {
                                    $editPasswordModal.modal('hide');

                                    // Remove success class, otherwise it is still displayed, if the user opens the modal again
                                    $passwordInputParentDiv.removeClass('has-success has-error');

                                    // Empty the password input fields
                                    $passwordInput.val('');
                                }, 400);
                            } else {
                                $('#showErrorInModal').html(data['message']);
                            }
                        },
                        error: function () {
                            $('#showErrorInModal').html('Submit failed!');
                        }
                    });
                }
            });
        },
        addUserModal: function() {
            var $createUserModal = $('#createUserModal'),
                $usernameNew = $('#usernameNew'),
                $showErrorInCreateUserModal = $('#showErrorInCreateUserModal'),
                url = require.toUrl('../config/user'),
                $passwordInputCreateUser = $('.passwordInputCreateUser'),
                $passwordInputCreateUserParentDiv = $passwordInputCreateUser.parent('div'),
                $usernameNewParentDiv = $usernameNew.parent('div');

            $createUserModal.once('shown.bs.modal', function () {
                $usernameNew.focus();
            });

            // Remove error, when input field is filled
            $passwordInputCreateUser.once('click', function() {
                $passwordInputCreateUserParentDiv.removeClass('has-error');
            });

            $usernameNew.once('click', function() {
                $usernameNewParentDiv.removeClass('has-error');
            });

            $('#saveUserButton').once('click', function() {
                var usernameNewVal = $usernameNew.val(),
                    inputPasswordNewVal = $('#inputPasswordNew').val(),
                    inputPasswordRepeatNewVal = $('#inputPasswordRepeatNew').val();

                if ($passwordInputCreateUser.val() === '' || inputPasswordNewVal !== inputPasswordRepeatNewVal) {
                    $passwordInputCreateUserParentDiv.addClass('has-error');
                } else {
                    $passwordInputCreateUserParentDiv.removeClass('has-error').addClass('has-success');
                }

                if (usernameNewVal === '') {
                    $usernameNewParentDiv.addClass('has-error');
                } else {
                    $usernameNewParentDiv.removeClass('has-error').addClass('has-success');
                }

                // Only close modal on success
                if ($passwordInputCreateUserParentDiv.hasClass('has-success') && $usernameNewParentDiv.hasClass('has-success')) {
                    $.ajax({
                        url: url + '/add',
                        data: {
                            "username": usernameNewVal,
                            "password": inputPasswordNewVal
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                // Wait a bit to inform the user of the success
                                setTimeout(function() {
                                    $createUserModal.modal('hide');

                                    // Remove success class, otherwise it is still displayed, if the user opens the modal again
                                    $passwordInputCreateUserParentDiv.removeClass('has-success has-error');
                                    $usernameNewParentDiv.removeClass('has-success has-error');
                                    $passwordInputCreateUser.val('');
                                    $usernameNew.val('');
                                }, 400);

                                $createUserModal.once('hidden.bs.modal', function() {
                                    return mylibs.load_workspace(url);
                                });
                            } else {
                                $usernameNewParentDiv.removeClass('has-success').addClass('has-error');
                                $passwordInputCreateUserParentDiv.removeClass('has-success').addClass('has-error');
                                $showErrorInCreateUserModal.html(data['message']);
                            }
                        },
                        error: function () {
                            $showErrorInCreateUserModal.html('Submit failed!');
                        }
                    });
                }
            });
        },
        table: function() {
            // delete user
            $('#userTable').once('click', '.deleteUserSpan', function() {
                var url = require.toUrl('../config/user');
                $.ajax({
                    url: url + '/delete',
                    data: {
                        "username": $(this).closest('tr').find('.username').text()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        if (data['code'] === 0) {
                            return mylibs.load_workspace(url);
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
    };
});