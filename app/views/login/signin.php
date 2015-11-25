<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin login</title>
<div class="container">
    <div class="container-small">
        <form method="post" class="form-signin" autocomplete="off">
            <h2 class="form-signin-header">phpietadmin</h2>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            </div>
            <div class="form-group">
                <input type="password" name="password1" class="form-control" placeholder="Password" required>
                <div class="show-password">
                    <input type="checkbox" id="show-password"> <label for="show-password">Show password</label>
                </div>
            </div>
            <div class="form-group error"></div>
            <input class="btn btn-lg btn-success btn-block" type='submit' value='Login'>
        </form>
    </div>
</div>

<script>
    require(['common'], function () {
        require(['pages/login', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.signIn();
            });
        });
    });
</script>