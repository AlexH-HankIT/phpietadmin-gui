<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Service</li>
    </ol>
    <div class = "jumbotron">
        <form method = "post">
            <input class="btn btn-primary" type="submit" name="start" value="Start" />
            <input class="btn btn-primary" type="submit" name="stop" value="Stop" />
            <input class="btn btn-primary" type="submit" name="restart" value="Restart" />
        </form>
    </div>
    <?php if (!empty($data)) { ?>
    <div class = "jumbotron">
            <p> <?php printf(htmlspecialchars($data)) ?></p>
    </div>
    <?php } ?>
</div>