<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Objects</li>
    </ol>
</div>

<div class="container">
    <table id="objectstable" class = "table table-striped searchabletable">
        <thead>
            <tr>
                <th class="col-md-2">Type</th>
                <th class="col-md-4">Name</th>
                <th class="col-md-4">Value</th>
                <th class="col-md-1"><a href="#" id="addobjectrowbutton"><span class="glyphicon glyphicon-plus glyphicon-20" aria-hidden="true"></span></a></th>
                <th class="col-md-1"></th>
            </tr>
        </thead>

        <tbody id="addobjectstbody">
        <?php if (is_array($data['objects'])) { ?>
            <?php foreach ($data['objects'] as $objects) { ?>
                <tr>
                    <td hidden class="id"><?php echo htmlspecialchars($objects['objectid']); ?></td>
                    <td class="col-md-2 type"><?php echo htmlspecialchars($objects['type']); ?></td>
                    <td class="col-md-4 objectname"><?php echo htmlspecialchars($objects['name']); ?></td>
                    <td class="col-md-4 objectvalue"><?php echo htmlspecialchars($objects['value']); ?></td>
                    <td class="col-md-1"><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
                    <td class="col-md-1"></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/objecttable']);
    });
</script>