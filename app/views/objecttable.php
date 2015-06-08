<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Objects</li>
    </ol>
</div>

<div class="container">
    <table id="objectstable" class = "table table-striped searchabletable">
        <thead>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Value</th>
                <th><a href="#" id="addobjectrowbutton"><span class="glyphicon glyphicon-plus glyphicon-20" aria-hidden="true"></span></a></th>
                <th></th>
            </tr>
        </thead>

        <tbody id="addobjectstbody">
        <?php if (is_array($data['objects'])) { ?>
            <?php foreach ($data['objects'] as $objects) { ?>
                <tr>
                    <td hidden class="id"><?php echo htmlspecialchars($objects['objectid']); ?></td>
                    <td class="type"><?php echo htmlspecialchars($objects['type']); ?></td>
                    <td class="objectname"><?php echo htmlspecialchars($objects['name']); ?></td>
                    <td class="objectvalue"><?php echo htmlspecialchars($objects['value']); ?></td>
                    <td><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
                    <td><!--<a href="#" class="editobjectrow"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>--></td>
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