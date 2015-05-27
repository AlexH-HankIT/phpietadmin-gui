<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Objects</li>
    </ol>
</div>

<div class="container">
    <table id="objectstable" class = "table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Value</th>
                <th><a href="#" id="addobjectrowbutton"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
                <th></th>
            </tr>
        </thead>

        <tbody id="addobjectstbody">

            <?php foreach ($data['objects'] as $objects) { ?>
            <tr>
                <td hidden class="id"><?php echo $objects['objectid'] ?></td>
                <td class="type"><?php echo $objects['type'] ?></td>
                <td><?php echo $objects['name'] ?></td>
                <td class="objectname"><?php echo $objects['name'] ?></td>
                <td class="objectvalue"><?php echo $objects['value'] ?></td>
                <td><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                <td><!--<a href="#" class="editobjectrow"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>--></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>