<div id="targetdeletecontent"  class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete target</li>
    </ol>
    <div class="jumbotron">
            <select id="targetdelete" class="form-control">
                <option id="default">Select a target to delete</option>
                <?php foreach ($data as $value) { ?>
                    <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
                <?php } ?>
            </select>
            <br />
            <input id="deletetargetbutton" class="btn btn-danger" type="submit" value="Delete">
    </div>
</div>

<script>
    require(['common'],function() {
        require(['pages/deletetarget']);
    });
</script>