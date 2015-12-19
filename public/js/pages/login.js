define(['jquery', 'vibrate', 'hideShowPassword'], function ($, vibrate) {
    return {
        signIn: function () {
            var $username = $('.form-control[name="username"]'),
                $password = $('.form-control[name="password1"]'),
                $button = $('input[type="submit"]'),
                $error = $('div.error');

            $('#show-password').change(function () {
                $password.hideShowPassword($(this).prop('checked'));
            });

            $('form.form-signin').submit(function () {
                $button.val('Login');
                $error.text('');

                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: {
                        'username': $username.val(),
                        'password1': $password.val()
                    },
                    success: function (data) {
                        $button.val('Connecting...');
                        if (data['status'] !== 'success') {
                            $('.form-signin').vibrate({
                                frequency: 5000,
                                spread: 5,
                                duration: 400
                            });
                            $error.text(data['message']);
                            $button.val('Login');
                        } else {
                            window.location.href = data['url'];
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                return false;
            });
        },
        firstSignIn: function() {
            var $button = $('input[type="submit"]'),
                $username = $('.form-control[name="username"]'),
                $authCode = $('.form-control[name="auth_code"]'),
                $password1 = $('.form-control[name="password1"]'),
                $password2 = $('.form-control[name="password2"]'),
                $error = $('div.error');

            $('form.form-signin').submit(function () {
                $button.val('Create');
                $error.text('');

                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: {
                        'auth_code': $authCode.val(),
                        'username': $username.val(),
                        'password1': $password1.val(),
                        'password2': $password2.val()
                    },
                    success: function (data) {
                        $button.val('Creating...');
                        if (data['status'] !== 'success') {
                            $('.form-signin').vibrate({
                                frequency: 5000,
                                spread: 5,
                                duration: 400
                            });

                            $error.text(data['message']);
                            $button.val('Create');
                        } else {
                            window.location.href = data['url'];
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                return false;
            });
        }
    };
});