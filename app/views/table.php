<div class="workspacedirect">
    <div class = "container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Overview</a></li>
                <li class='active'><?php echo htmlspecialchars($data['title']); ?></li>
            </ol>

            <div class="table-responsive">
                <table class = "table table-striped table-in-panel">
                    <thead>
                    <tr>
                        <?php foreach ($data['heading'] as $value) { ?>
                            <th><?php echo htmlspecialchars($value); ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['body'] as $value) { ?>
                        <tr>
                            <?php foreach ($value as $key => $td) { ?>
                                <td><?php echo htmlspecialchars($td); ?></td>
                                <?php if ($key === 'VFree') { ?>
                                    <td class="col-md-4">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $value['VSize'] ?>" aria-valuemin="0" aria-valuemax="<?php echo $value['VSize'] ?>" style="width: <?php echo ($td * 100) / ($value['VSize'] - $td) ?>%"></div>
                                        </div>
                                    </td>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>