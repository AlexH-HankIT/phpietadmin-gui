<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete user</li>
    </ol>
</div>

<div class = "container">
    <div class = "jumbotron">
        <div class="row">
            <div class="col-md-11">
                <select name="target" id="targetselection" class="form-control">
                    <option id="default">Select target...</option>
                    <?php foreach ($data['targets'] as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"> <?php echo htmlspecialchars($value); ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-1">
                <button id="deleteuserbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
            </div>
        </div>
    </div>

</div>

<div id="deleteusertablediv"></div>

<script>
    require(['common'],function() {
        require(['pages/deleteuser']);
    });
</script>