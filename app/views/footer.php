<div class = 'navbar navbar-default navbar-fixed-bottom'>
    <div class = 'container'>
        <div class='row'>

            <div class='col-sm-11'>
                <div id='ietdstatus'>
                    <?php if ($data[1] == 0) { ?>
                        <a class = 'navbar-btn btn-success btn'><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> ietd running</a>
                    <?php } else { ?>
                        <a class = 'navbar-btn btn-danger btn'><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> ietd not running</a>
                    <?php } ?>
                </div>
            </div>

            <!--<div class='col-sm-5 navbar-text'>
                <p>Last status:</p>
                <span id='status-indicator' class='label'></span>
            </div>-->

            <div class='col-sm-1'>
                <a style="font-size:12px" href='https://github.com/MrCrankHank' target='new' class="navbar-text">Created by MrCrankHank</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>