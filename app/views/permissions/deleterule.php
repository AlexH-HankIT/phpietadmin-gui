<script>
    $('.searchabletable').filterTable({minRows:0});
</script>

<div class="container">
    <div class = "row">
        <?php if (isset($data['deleteruleobjectstable']) && !empty($data['deleteruleobjectstable'])) { ?>
            <div class = "col-md-5">
                <ol class="breadcrumb">
                    <li class="active">Objects</li>
                </ol>
                <table class="table table-striped searchabletable" id="deleteruleobjectstable">
                    <thead>
                        <tr>
                            <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['deleteruleobjectstable'] as $row) { ?>
                            <tr>
                                <td><input class="objectdeletecheckbox" type="checkbox"/></td>
                                <td class="objectid" hidden><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="objectvalue"><?php echo htmlspecialchars($row['value']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class = "col-md-5">
                <ol class="breadcrumb">
                    <li class="active">Objects</li>
                </ol>
                <h4><p align="center">No objects</p></h4>
            </div>
        <?php } ?>

        <?php if (isset($data['deleteruleorphanedtable']) && !empty($data['deleteruleorphanedtable'])) { ?>
            <div class = "col-md-5">
                <ol class="breadcrumb">
                    <li class="active">Orphans (No object available)</li>
                </ol>

                <table class="table table-striped searchabletable" id="deleteruleorphanedtable">
                    <thead>
                        <tr>
                            <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['deleteruleorphanedtable'] as $row) { ?>
                            <tr>
                                <td><input type="Radio" name="deleteobjectradio"/></td>
                                <td class="objectvalue"><?php echo htmlspecialchars($row); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class = "col-md-2">
                <div id="deleterulebutton"><button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button></div>

                <div class="btn-group top-buffer" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="deleteruletype" value="initiators.allow" checked="checked" /> Initiators
                    </label>

                    <label class="btn btn-default">
                        <input type="Radio" name="deleteruletype" value="targets.allow" /> Targets
                    </label>
                </div>
            </div>
        <?php } else { ?>
            <div class = "col-md-5">
                <ol class="breadcrumb">
                    <li class="active">Orphans (No object available)</li>
                </ol>
                <h4><p align="center">No orphans</p></h4>
            </div>
            <div class = "col-md-2">
                <div id="deleterulebutton"><button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button></div>

                <div class="btn-group top-buffer" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="deleteruletype" value="initiators.allow" checked="checked" /> Initiators
                    </label>

                    <label class="btn btn-default">
                        <input type="Radio" name="deleteruletype" value="targets.allow" /> Targets
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    require(['common'],function() {
        require(['pages/deleterule']);
    });
</script>