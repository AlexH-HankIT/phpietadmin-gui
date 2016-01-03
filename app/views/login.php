<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo WEB_PATH ?>/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/base.css" type="text/css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/login.css" type="text/css" rel="stylesheet">
<script data-main="<?php echo WEB_PATH ?>/js/common" src="<?php echo WEB_PATH ?>/js/lib/require.js"></script>
<title><?php echo PROJECT_NAME ?> login</title>
<div class="container">
    <form method="post" class="form-signin" autocomplete="off">
        <h2 class="form-signin-header"><?php echo PROJECT_NAME ?></h2>
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
            <input type="password" name="password1" class="form-control" placeholder="Password" required>
            <div class="show-password">
                <input type="checkbox" id="show-password"> <label for="show-password">Show password</label>
            </div>
        </div>
        <button class="btn btn-lg btn-primary btn-block button-width" data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Loading...' type="submit">Login</button>
        <div class="form-group formError"></div>
    </form>
</div>
<script>
    require(['common'], function () {
        require(['pages/login', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.login();
            });
        });
    });
</script>