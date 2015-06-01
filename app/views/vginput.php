<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Volume groups</li>
    </ol>
    <div id = 'vginput' class = "jumbotron">
        <form method="post">
            <select name="vg_post" multiple class="form-control" onchange="showlvinput(this.value)">
                <?php foreach ($data as $row) { ?>
                    <option value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                <?php } ?>
            </select>
            <br />
        </form>
    </div>
</div>
<div id="lv"></div>