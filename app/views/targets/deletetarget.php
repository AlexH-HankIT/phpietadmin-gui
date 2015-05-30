<div id="targetdeletecontent"  class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete target</li>
    </ol>
    <div class="jumbotron">
            <select name="target" id="targetdelete" class="form-control">
                <option id="default">Select a target to delete</option>
                <?php foreach ($data as $value) { ?>
                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
            <br />
            <input class="btn btn-danger" type="submit" value="Delete" onclick="return validatedeletetarget()">
    </div>
</div>