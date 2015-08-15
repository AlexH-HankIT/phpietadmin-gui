<div class="workspacedirect">
    <div class="container">
        <div class="row top-buffer">
            <div class='panel panel-default'>
                <ol class='panel-heading breadcrumb'>
                    <li><a href='#'>Overview</a></li>
                    <li class="active">Targets with luns</li>
                </ol>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Lun</th>
                            <th>tid</th>
                            <th>iqn</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="col-md-4"></th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php foreach (array_reverse($data) as $key => $row) { ?>
                                <tr class="clickable <?php if (isset($row['lun'])) echo 'active' ?>" data-toggle="collapse" id="row<?php echo $key ?>" data-target=".row<?php echo $key ?>">
                                    <td class="col-md-1"><?php if (isset($row['lun'])) echo '<a href="#"><span class="glyphicon glyphicon-20 glyphicon-plus"></span></a>' ?></td>
                                    <td class="col-md-2"><?php echo $row['tid'] ?></td>
                                    <td class="col-md-2"><?php echo $row['iqn'] ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="col-md-4"></td>
                                </tr>
                                <?php if (isset($row['lun'])) { ?>
                                    <tr class="collapse row<?php echo $key ?>">
                                        <th class="col-md-1">ID</th>
                                        <th class="col-md-1">State</th>
                                        <th class="col-md-1">IOType</th>
                                        <th class="col-md-2">IOMode</th>
                                        <th class="col-md-1">Blocks</th>
                                        <th class="col-md-1">Blocksize</th>
                                        <th class="col-md-4">Path</th>
                                    </tr>
                                    <?php foreach ($row['lun'] as $lun) { ?>
                                        <tr class="collapse row<?php echo $key ?>">
                                            <td class="col-md-1"><?php echo $lun['id'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['state'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['iotype'] ?></td>
                                            <td class="col-md-2"><?php echo $lun['iomode'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['blocks'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['blocksize'] ?></td>
                                            <td class="col-md-4"><?php echo $lun['path'] ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>