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
        <p>Only unused volumes are displayed!</p>
        <button class="btn btn-danger" type='submit' id='logicalvolumedeletebutton'>Delete</button>
    </div>
</div>