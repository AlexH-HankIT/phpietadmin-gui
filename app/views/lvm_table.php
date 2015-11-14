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
                    <?php foreach ($data['body'] as $key => $value) { ?>
                        <tr>
                            <?php foreach ($value as $td) { ?>
                                <td><?php echo htmlspecialchars($td); ?></td>
                            <?php } ?>

                            <?php if (isset($data['meta'])) { ?>
                            <td class="col-md-4">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-<?php echo htmlspecialchars($data['meta'][$key]['type']) ?> progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $data['meta'][$key]['width'] ?>%; min-width: 3em;"> <?php echo htmlspecialchars($data['meta'][$key]['width']) ?> %
                                </div>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>