<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete logical volumes</li>
    </ol>
</div>

<div id="logicalvolumedeletecontent" class = "container">
    <div class = "jumbotron">
        <p>
            <select id="logicalvolumedeleteselection" class="form-control">
                <option id="default">Select...</option>
                <?php foreach ($data as $row) { ?>
                    <option value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                <?php } ?>
            </select>
        <p>
        <button id="logicalvolumedeletebutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
    </div>
</div>

<script>
    require(['common'],function() {
        require(['pages/lvmdelete']);
    });
</script>