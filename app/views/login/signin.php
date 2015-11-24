<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.min.css" type="text/css" rel="stylesheet">
<link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
<title>phpietadmin login</title>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="form-signin-heading">Please sign in</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post" class="form-signin">
                <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
                <input type="password" name="password1" id="inputPassword2" class="form-control" placeholder="Password" required>
                <input class="btn btn-lg btn-primary btn-block" type='submit' value='Login'>
            </form>
        </div>
    </div>
</div>