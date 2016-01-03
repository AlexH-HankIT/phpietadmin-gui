define(['jquery', 'vibrate', 'hideShowPassword'], function ($, vibrate) {
    return {
        login: function () {
            var $username = $('.form-control[name="username"]'),
                $password = $('.form-control[name="password1"]'),
                $button = $('button[type="submit"]'),
                $error = $('div.formError');

            $('#show-password').change(function () {
                $password.hideShowPassword($(this).prop('checked'));
            });

            $('form.form-signin').submit(function () {
                $error.text('');
                $button.button('loading');
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: {
                        'username': $username.val(),
                        'password1': $password.val()
                    },
                    success: function (data) {
                        if (data['status'] !== 'success') {
                            $button.button('reset');
                            $('.form-signin').vibrate({
                                frequency: 5000,
                                spread: 5,
                                duration: 400
                            });
                            $error.text(data['message']);
                        } else {
                            window.location.href = data['url'];
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        $button.button('reset');
                    }
                });
                return false;
            });
        }
    };
});