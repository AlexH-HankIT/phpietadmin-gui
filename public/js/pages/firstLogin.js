define(['jquery', 'vibrate'], function($, vibrate) {
    return {
        firstLogin: function() {
            var $authCode = $('.form-control[name="authcode"]'),
                $username = $('.form-control[name="username"]'),
                $password1 = $('.form-control[name="password1"]'),
                $password2 = $('.form-control[name="password2"]'),
                $button = $('input[type="submit"]'),
                $error = $('div.formError');

            $('form.form-signin').submit(function () {
                $button.val('Create');
                $error.text('');

                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: {
                        'authCode': $authCode.val(),
                        'username': $username.val(),
                        'password1': $password1.val(),
                        'password2': $password2.val()
                    },
                    success: function (data) {
                        $button.val('Connecting...');
                        if (data['code'] !== 0) {
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
        }
    }
});