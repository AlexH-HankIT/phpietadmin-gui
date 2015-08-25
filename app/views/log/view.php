<div class="workspacedirect">
    <div class="container">
        <div class="table-responsive">
            <table data-toggle="table" class = "table table-bordered table-striped">
                <thead>
                <tr>
                    <?php foreach ($data['heading'] as $value) { ?>
                        <th><?php echo htmlspecialchars($value); ?></th>
                    <?php } ?>
                    <!-- data-halign="center" -->
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['body'] as $value) { ?>
                    <tr>
                        <td class="col-md-3"><?php echo htmlspecialchars(gmdate("D M j G:i:s T Y", $value[0])) ?></td>
                        <td class="col-md-1"><?php echo htmlspecialchars($value[1]) ?></td>
                        <td class="col-md-2"><?php echo htmlspecialchars($value[2]) ?></td>
                        <td class="col-md-1"><?php echo htmlspecialchars($value[3]) ?></td>
                        <td class="col-md-2"><?php echo htmlspecialchars($value[4]) ?></td>
                        <td class="col-md-1">
                            <?php if ($value[5] == 'success') { ?>
                                <span class="glyphicon  glyphicon glyphicon-ok glyphicon-20" aria-hidden="true"></span>
                            <?php } else { ?>
                                <span class="glyphicon  glyphicon glyphicon-remove glyphicon-20" aria-hidden="true"></span>
                            <?php } ?>
                        </td>
                        <td class="col-md-1"><?php echo htmlspecialchars($value[6]) ?></td>
                        <td class="col-md-1"><?php echo htmlspecialchars($value[7]) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>