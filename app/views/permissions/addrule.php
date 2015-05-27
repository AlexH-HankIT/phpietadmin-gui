<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add rule</li>
    </ol>
</div>

<div class = "container">
        <select name="target" id="targetselection" class="form-control">
            <option id="default">Select target...</option>
            <?php foreach ($data['targets'] as $value) { ?>
                <option value="<?php echo $value ?>"> <?php echo $value ?> </option>
            <?php } ?>
        </select>

        <br />
        <br />

        <div class="row">
            <div class="col-md-2">
                <input id="initiatorcheckbox" type="Radio" name="type" value="initiator" checked="checked" /> Initiator
            </div>
            <div class="col-md-7">
                <input id="targetcheckbox" type="Radio" name="type" value="target" /> Target
            </div>
            <div class="col-md-1">
                <div id="addallowrulebutton" class="col-md-1"><button type="button" class="btn btn-success">Allow</button></div>
            </div>
            <div class="col-md-1">
                <div id="adddenyrulebutton" class="col-md-1"><button type="button" class="btn btn-danger">Deny</button></div>
            </div>
        </div>

        <br />
        <br />

        <table id="objectstable" class="table table-bordered">
            <thead>
                <tr>
                    <th><span class="glyphicon glyphicon glyphicon-ok green"></span></th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
            </thead>

            <tbody id="objectselection">
                <?php foreach ($data['objects'] as $value ) { ?>
                    <tr>
                        <td><input type="Radio" name="objectradio"/></td>
                        <td class="objectid" hidden><?php echo $value['objectid'] ?></td>
                        <td><?php echo $value['type'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['value'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
</div>