<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin login</title>
<div class="container">
    <form method="post" class="form-signin" autocomplete="off">
        <h2 class="center form-signin-heading">Please create a user</h2>
        <input type="password" name="auth_code" id="auth_code" class="form-control" placeholder="Auth code" required autofocus>
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required>
        <input type="password" name="password1" id="inputPassword1" class="form-control" placeholder="Password" required>
        <input type="password" name="password2" id="inputPassword2" class="form-control" placeholder="Repeat password" required>
        <input class="btn btn-lg btn-success btn-block" type='submit' value='Create'>
        <div class="form-group error"></div>
    </form>
</div>

<script>
    require(['common'], function () {
        require(['pages/login', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.firstSignIn();
            });
        });
    });
</script>