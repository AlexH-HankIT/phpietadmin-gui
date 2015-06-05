<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete rule</li>
    </ol>
</div>

<div class = "container">
    <div class = "jumbotron">
        <div class="row">
            <div class="col-md-6">
                <select name="target" id="targetselection" class="form-control">
                    <option value="default" id="default">Select target...</option>
                    <?php foreach ($data['targets'] as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"> <?php echo htmlspecialchars($value); ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-5">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="deleteruletype" value="initiators.allow" checked="checked" /> Initiators allow
                    </label>

                    <label class="btn btn-default">
                        <input type="Radio" name="deleteruletype" value="targets.allow" /> Targets allow
                    </label>
                </div>
            </div>

            <div class="col-md-1">
                <div id="deleterulebutton" class="col-md-1"><button type="button" class="btn btn-danger">Delete</button></div>
            </div>
        </div>
    </div>

    <div id="deleteruletable"></div>
</div>