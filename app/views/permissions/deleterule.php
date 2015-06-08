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

            <div class="col-md-4">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="deleteruletype" value="initiators.allow" checked="checked" /> Initiators
                    </label>

                    <label class="btn btn-default">
                        <input type="Radio" name="deleteruletype" value="targets.allow" /> Targets
                    </label>
                </div>
            </div>

            <div class="col-md-1">
                <div id="deleterulebutton" class="col-md-1"><button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button></div>
            </div>
        </div>
    </div>

    <div id="deleteruletable"></div>
</div>

<script>
    require(['common'],function() {
        require(['pages/deleterule']);
    });
</script>