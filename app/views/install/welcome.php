<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/welcome.css" type="text/css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin installation</title>
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
                <button id="createFirstUserButton" class="btn btn-primary btn-lg" disabled>2. Create first user</button>
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