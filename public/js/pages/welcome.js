define(['jquery', 'bootstrap'], function ($) {
    return {
        createDatabase: function () {
            var $createDatabaseButton = $('#createDatabaseButton'),
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
            var $addUserModal = $('#addUserModal');

            // On modal show
            $addUserModal.on('shown.bs.modal', function () {
                $('input[name="authCode"]').focus();
            });

            // On modal hide
            $addUserModal.on('hidden.bs.modal', function () {
                var $this = $(this);
                $('input[name="password1"]', $this).val('');
                $('input[name="password2"]', $this).val('');
                $('input[name="username"]', $this).val('');
                $('input[name="authCode"]', $this).val('');
            });

            $('div.modal-body').children('form').submit(function () {
                var $addUserModal = $('#addUserModal'),
                    $password1 = $('input[name="password1"]'),
                    $password2 = $('input[name="password2"]'),
                    $username = $('input[name="username"]'),
                    $authCode = $('input[name="authCode"]'),
                    password1Val = $password1.val(),
                    password2Val = $password2.val(),
                    usernameVal = $username.val(),
                    authCodeVal = $authCode.val();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'password1': password1Val,
                        'password2': password2Val,
                        'username': usernameVal,
                        'authCode': authCodeVal
                    },
                    success: function (data) {
                        if (data.code !== 0) {
                            $('div.error').text(data.message)
                        } else {
                            $addUserModal.modal('hide');
                            window.location = "auth/login";
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