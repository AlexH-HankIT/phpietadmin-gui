<div id='footer' class = 'navbar navbar-default navbar-fixed-bottom'>
    <div class = 'container'>
        <div class='hidden-xs hidden-sm'>
            <div class='row'>
                <div class='col-md-2'>
                    <div id='ietdstatus'>
                        <?php if ($data[1] == 0) { ?>
                            <a class='navbar-btn btn-success btn'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> ietd running</a>
                        <?php } else { ?>
                            <a class='navbar-btn btn-danger btn'><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> ietd not running</a>
                        <?php } ?>
                    </div>
                </div>
                <div class='col-md-offset-9 col-md-1'>
                    <a style="font-size:10px" href='https://github.com/MrCrankHank' target='new' class='navbar-text'>Created by MrCrankHank</a>
                </div>
            </div>
        </div>

        <div class='hidden-md hidden-lg'>
            <div class='row text-center'>
                <div class='ol-xs-12'>
                    <div id='ietdstatus'>
                        <?php if ($data[1] == 0) { ?>
                            <a class='navbar-btn btn-success btn'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> ietd running</a>
                        <?php } else { ?>
                            <a class='navbar-btn btn-danger btn'><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> ietd not running</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>