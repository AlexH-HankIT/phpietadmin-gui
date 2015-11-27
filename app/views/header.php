<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/phpietadmin/css/bootstrap.css" rel="stylesheet">
<link href="/phpietadmin/css/bootstrap-table.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery.qtip.css" rel="stylesheet">
<link href="/phpietadmin/css/sweetalert.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery-ui.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery-ui-slider-pips.css" rel="stylesheet">
<link href="/phpietadmin/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
<link href="/phpietadmin/css/nprogress.css" rel="stylesheet">
<link href="/phpietadmin/css/my.css" rel="stylesheet">
<script data-main="/phpietadmin/js/common" src="/phpietadmin/js/lib/require.js"></script>
<title>phpietadmin<?php if (isset($data['hostname'])) echo ' on ' . htmlspecialchars($data['hostname']); ?></title>
<noscript>
	<div class="container">
		<div class="alert alert-warning" role="alert">
			<h3 align="center">Warning - JavaScript is disabled. This application won't work correctly!</h3>
		</div>
	</div>
</noscript>
<div hidden id="offlinemessage">
    Server connection failed... <img src="/phpietadmin/img/ajax-loader.gif" alt="Loading">
</div>
<span id="mq-detector">
    <span class="visible-xs"></span>
    <span class="visible-sm"></span>
    <span class="visible-md"></span>
    <span class="visible-lg"></span>
</span>