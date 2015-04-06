<?php
    class Layout {
        function __construct() { ?>
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                "http://www.w3.org/TR/html4/loose.dtd">
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                <meta name="content-language" content="de">
                <link href="/phpietadmin/css/design.css" type="text/css" rel="stylesheet">
                <script type="text/javascript" src=/phpietadmin/js/scripts.js></script>
            </head>
            <title>phpietadmin</title>
            <body>
        <?php }

        function print_nav() { ?>
            <nav id="nav01"></nav>
            <script type="text/javascript">showMenu()</script>
        <?php }

        function print_title($var) { ?>
            <div id="main">
            <h1><?php echo $var ?></h1>
        <?php }

        function print_error($e) {
            $error = $e->getMessage(); ?>
            <div id="leftDiv">
                <h4><?php echo $error ?></h4>
            </div>
        <?php }

        function print_footer() { ?>
            <div style="display: block; clear: both;"></div>
            </div>
            <?php require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/mondata.php"; ?>
            </body>
            </html>
        <?php }
    }
?>