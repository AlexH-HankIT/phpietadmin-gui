define(['jquery', 'bootstrap'], function ($) {
    return {
        createDatabase: function () {
            var $createDatabaseButton,
                $createFirstUserButton;

            $createDatabaseButton = $('#createDatabaseButton');
            $createFirstUserButton = $('#createFirstUserButton');

            if ($createDatabaseButton.next('span').hasClass('icon-success')) {
                $createFirstUserButton.attr('disabled', false);
            }

            $createDatabaseButton.on('click', function () {
                var $span = $(this).next('span');

                // If the span has the icon-success class
                // the database is already created
                if (!$span.hasClass('icon-success')) {
                    $.ajax({
                        url: '/phpietadmin/install/database',
                        type: 'get',
                        success: function (data) {
                            if (data.code !== 0) {
                                $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-ok icon-success');
                                $createFirstUserButton.attr('disabled', false);
                            } else {
                                $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                            }
                        },
                        error: function () {
                            $span.removeClass('glyphicon-remove icon-danger').addClass('glyphicon-warning-sign icon-warning');
                        }
                    });
                }
            });
        },
        addUserModal: function () {
            var $password,
                $passwordRepeat,
                $username,
                $authCode,
                $addUserModal,
                $passwordVal,
                $passwordRepeatVal,
                $usernameVal,
                $authCodeVal;

            $password = $('#addUserPasswordInput');
            $passwordRepeat = $('#addUserPasswordInputRepeat');
            $username = $('#addUserUsernameInput');
            $authCode = $('#addUserAuthCode');
            $addUserModal = $('#addUserModal');

            $passwordVal = $password.val();

            // On modal show
            $addUserModal.on('shown.bs.modal', function () {
                $('#addUserAuthCode').focus();
            });

            // On modal hide
            $addUserModal.on('hidden.bs.modal', function () {
                $('#addUserAuthCode').val('');
            });

            $('div.modal-body').children('form').submit(function () {
                console.log($(this).children('input[name="authCode"]'));

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: {
                        'password1': $password.val(),
                        'password2': $passwordRepeat.val(),
                        'username': $username.val(),
                        'authCode': $authCode.val()
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.code !== 0) {
                            $('div.error').text(data.message)
                        } else {
                            $addUserModal.modal('hide');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });

                return false;
            });
        }
    }
});