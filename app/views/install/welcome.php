<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/welcome.css" type="text/css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin installation</title>
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title center">Please create a user</h3>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo WEB_PATH ?>/install/user">
                    <div class="form-group">
                        <input type="password" name="authCode" class="form-control" placeholder="Auth code" required autofocus>
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password1" class="form-control" placeholder="Password" required>
                        <input type="password" name="password2" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <div class="form-group error"></div>
                </form>
            </div>
            <div class="modal-footer">
                <input class="btn btn-lg btn-primary btn-block" type='submit' value='Create'>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="form-signin">
        <h2 class="form-signin-heading">Installation</h2>
        <div class="row top-buffer center">
            <div class="col-xs-12">
                <button id="createDatabaseButton" class="btn btn-primary btn-lg">1. Create database</button>
                <?php if ($data['database'] === true) { ?>
                    <span class="hidden-xs glyphicon glyphicon-ok glyphicon-30 icon-success" aria-hidden="true"></span>
                <?php } else { ?>
                    <span class="hidden-xs glyphicon glyphicon-remove glyphicon-30 icon-danger" aria-hidden="true"></span>
                <?php } ?>
            </div>
        </div>
        <div class="row top-buffer center">
            <div class="col-md-12">
                <button id="createFirstUserButton" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addUserModal" disabled>2. Create first user</button>
                <span class="hidden-xs glyphicon glyphicon-remove glyphicon-30 icon-danger" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</div>
<script>
    require(['common'],function() {
        require(['pages/welcome', 'domReady'],function(methods, domReady) {
            domReady(function () {
                methods.addUserModal();
                methods.createDatabase();
            });
        });
    });
</script>