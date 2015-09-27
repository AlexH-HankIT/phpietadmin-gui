<!DOCTYPE html>
<html lang="en-US">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.min.css" rel="stylesheet">
<link href="/phpietadmin/css/bootstrap-table.min.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery.qtip.min.css" rel="stylesheet">
<link href="/phpietadmin/css/sweetalert.min.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery-ui.min.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery-ui-slider-pips.min.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet">
<link href="/phpietadmin/css/my.css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<noscript>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            <h3 align="center">Warning - JavaScript is disabled. This application won't work correctly!</h3>
        </div>
    </div>
</noscript>
<title>phpietadmin<?php if (isset($data['hostname'])) echo ' on ' . htmlspecialchars($data['hostname']); ?></title>

<body>
<div hidden id="offlinemessage">
    Server connection failed... <img src="/phpietadmin/img/ajax-loader.gif">
</div>