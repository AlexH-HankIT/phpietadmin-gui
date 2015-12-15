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
        <div align="center" class="row top-buffer">
            <div class="col-xs-12">
                <button id="createDatabaseButton" class="btn btn-success btn-lg">1. Create database</button>
                <span class="hidden-xs glyphicon glyphicon-remove glyphicon-30 icon-danger" aria-hidden="true"></span>
            </div>
        </div>
        <div align="center" class="row top-buffer">
            <div class="col-md-12">
                <button id="createFirstUserButton" class="btn btn-success btn-lg">2. Create first user</button>
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