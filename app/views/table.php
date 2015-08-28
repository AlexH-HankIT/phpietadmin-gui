<div id = 'workspace'>
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

                                    <?php // calculated progress
                                    $vsize = intval($value['VSize']);
                                    $width = intval(($vsize - intval($value['VFree'])) * 100 / $vsize);

                                    if ($width <= 60) {
                                        $bar = 'success';
                                    } else if ($width >= 81) {
                                        $bar = 'danger';
                                    } else if ($width >= 61) {
                                        $bar = 'warning';
                                    } else {
                                        $bar = 'info';
                                    }
                                    ?>

                                    <td class="col-md-4">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-<?php echo $bar ?> progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width ?>%; min-width: 3em;"> <?php echo $width ?> %
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