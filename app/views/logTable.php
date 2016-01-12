<div class = 'workspace'>
    <div class = "container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Logs</a></li>
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
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>