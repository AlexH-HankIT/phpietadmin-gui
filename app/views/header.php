<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo WEB_PATH ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/bootstrap-table.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/jquery.qtip.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/sweetalert.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/jquery-ui.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/jquery-ui-slider-pips.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/nprogress.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/bootstrap-select.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/base.css" rel="stylesheet">
<link href="<?php echo WEB_PATH ?>/css/my.css" rel="stylesheet">
<script>
    // Define require js base url
    var require = { baseUrl: "<?php echo WEB_PATH . '/js'?>" };
</script>

<script data-main="<?php echo WEB_PATH ?>/js/common" src="<?php echo WEB_PATH ?>/js/lib/require.js"></script>
<title>phpietadmin<?php if (isset($data['hostname'])) echo ' on ' . htmlspecialchars($data['hostname']); ?></title>
<div class="container">
    <noscript>
        <div class="alert alert-warning" role="alert">
            <h3 class="center">Warning - JavaScript is disabled. This application won't work correctly!</h3>
        </div>
    </noscript>
</div>
<div hidden id="offlinemessage">
    Server connection failed... <img src="<?php echo WEB_PATH ?>/img/ajax-loader.gif" alt="Loading">
</div>
<span id="mq-detector">
    <span class="visible-xs"></span>
    <span class="visible-sm"></span>
    <span class="visible-md"></span>
    <span class="visible-lg"></span>
</span>