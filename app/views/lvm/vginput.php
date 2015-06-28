<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Volume groups</li>
    </ol>
    <div class = "jumbotron">
            <select id="vgselection" multiple class="form-control">
                <?php foreach ($data as $row) { ?>
                    <option value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                <?php } ?>
            </select>
    </div>
</div>
<div id="lv"></div>

<script>
    require(['common'],function() {
        require(['pages/vginput']);
    });
</script>