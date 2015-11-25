define(['jquery', 'vibrate', 'hideShowPassword'], function ($, vibrate) {
    var methods;
    return methods = {
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

                            $error.text(data['message']).show();
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
        }
    };
});