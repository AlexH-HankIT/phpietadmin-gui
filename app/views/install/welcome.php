<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/welcome.css" type="text/css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin installation</title>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add first user</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon input-group-addon-16">Auth code</span>
                            <input type="text" class="form-control" id="addUserAuthCode" placeholder="Auth code...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon input-group-addon-16">Username</span>
                            <input type="text" class="form-control" id="addUserUsernameInput" placeholder="Username...">
                        </div>
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <div class="input-group passwordInputGroup">
                            <span class="input-group-addon input-group-addon-16">Password</span>
                            <input type="text" class="form-control" id="addUserPasswordInput" placeholder="Password...">
                        </div>
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <div class="input-group passwordInputGroup">
                            <span class="input-group-addon input-group-addon-16">Repeat</span>
                            <input type="text" class="form-control" id="addUserPasswordInputRepeat" placeholder="Repeat...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-offset-3 col-md-3">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                    </div>
                    <div class="col-md-offset-1 col-md-3">
                        <button type="button" class="btn btn-success" id="createUserButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>
                    </div>
                </div>
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
                methods.test();
            });
        });
    });
</script>