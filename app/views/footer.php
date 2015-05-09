<div class = "navbar navbar-default navbar-fixed-bottom">
    <div class = "container">
        <a href = 'https://github.com/MrCrankHank' target = 'new' class = "navbar-text pull-right">Created by MrCrankHank</a>
        <div id="ietdstatus">
            <?php if ($data[1] == 0) { ?>
                <a class = "navbar-btn btn-success btn pull-left">ietd running</a>
            <?php } else { ?>
                <a class = "navbar-btn btn-danger btn pull-left">ietd not running</a>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>