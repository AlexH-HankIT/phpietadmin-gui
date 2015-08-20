<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/phpietadmin/css/bootstrap.css" type="text/css" rel="stylesheet">
    <link href="/phpietadmin/css/bootstrap-table.css" type="text/css" rel="stylesheet">
    <link href="/phpietadmin/css/my.css" type="text/css" rel="stylesheet">
    <noscript>
        <div class = "container">
            <div class="alert alert-warning" role="alert"><h3 align="center">Warning - JavaScript is disabled. This application won't work correctly!</h3></div>
        </div>
    </noscript>
    <title>phpietadmin</title>
</head>
<body>
<div class="container">
    <div class="alert alert-warning" role="alert"><h3 align="center"><?php echo htmlspecialchars($data); ?></h3></div>

    <form method = "post" class="form-signin">
        <input type="hidden" name="override">
        <input class="btn btn-lg btn-primary btn-block" type='submit' value='Override'>
    </form>
</div>
</body>
</html>