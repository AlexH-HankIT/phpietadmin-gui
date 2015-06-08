<script>
    $('.searchabletable').filterTable({minRows:0});
</script>

<div class = "row">
    <?php if (isset($data['deleteruleobjectstable']) && !empty($data['deleteruleobjectstable'])) { ?>
        <div class = "col-md-6">
            <ol class="breadcrumb">
                <li class="active">Objects</li>
            </ol>

            <table class="table table-bordered searchabletable" id="deleteruleobjectstable">
                <thead>
                    <tr>
                        <th><span class="glyphicon glyphicon glyphicon-ok green"></span></th>
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
    <?php } ?>

    <?php if (isset($data['deleteruleorphanedtable']) && !empty($data['deleteruleorphanedtable'])) { ?>
        <div class = "col-md-6">
            <ol class="breadcrumb">
                <li class="active">Orphans (No object available)</li>
            </ol>

            <table class="table table-bordered searchabletable" id="deleteruleorphanedtable">
                <thead>
                    <tr>
                        <th><span class="glyphicon glyphicon glyphicon-ok green"></span></th>
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
    <?php } ?>
</div>