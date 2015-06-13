<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add rule</li>
    </ol>
</div>

<div class = "container">
    <div class = "jumbotron">
        <div class="row">
            <div class="col-md-6">
                <select name="target" id="targetselection" class="form-control">
                    <option id="default">Select target...</option>
                    <?php foreach ($data['targets'] as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"> <?php echo htmlspecialchars($value); ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-4">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" id="initiatorcheckbox" name="type" value="initiator" checked="checked" /> Initiator
                    </label>

                    <label class="btn btn-default">
                        <input id="targetcheckbox" type="Radio" name="type" value="target" /> Target
                    </label>
                </div>
            </div>

            <div class="col-md-1">
                <div id="addallowrulebutton" class="col-md-1"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Allow</button></div>
            </div>
        </div>
    </div>
</div>

<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Objects</li>
    </ol>
    <table id="objectstable" class="table table-striped searchabletable">
        <thead>
        <tr>
            <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
            <th class="col-md-3">Type</th>
            <th class="col-md-4">Name</th>
            <th class="col-md-4">Value</th>
        </tr>
        </thead>

        <tbody id="objectselection">
        <?php if (is_array($data['objects'])) { ?>
            <?php foreach ($data['objects'] as $value ) { ?>
                <tr>
                    <td class="objectid" hidden><?php echo htmlspecialchars($value['objectid']); ?></td>
                    <td class="col-md-1"><input class="objectcheckbox" type="checkbox"/></td>
                    <td class="col-md-3"><?php echo htmlspecialchars($value['type']); ?></td>
                    <td class="col-md-4"><?php echo htmlspecialchars($value['name']); ?></td>
                    <td class="col-md-4"><?php echo htmlspecialchars($value['value']); ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/addrule']);
    });
</script>