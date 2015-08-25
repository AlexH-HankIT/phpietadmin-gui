<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
    <link href="/phpietadmin/css/login.css" type="text/css" rel="stylesheet">
    <title>phpietadmin login</title>
</head>
<body>
    <div class = "container">
        <form method = "post" class="form-signin">
            <h2 align="center" class="form-signin-heading">Please create a user</h2>
            <input type="password" name="auth_code" id="auth_code" class="form-control" placeholder="Auth code" required autofocus>
            <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required>
            <input type="password" name="password1" id="inputPassword1" class="form-control" placeholder="Password" required>
            <input type="password" name="password2" id="inputPassword2" class="form-control" placeholder="Repeat password" required>
            <input class="btn btn-lg btn-primary btn-block" type='submit' value='Create'>
        </form>
    </div>
</body>
</html>