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
                        <?php foreach ($data[0] as $value) { ?>
                            <th><?php echo htmlspecialchars($value); ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data[1] as $value) { ?>
                        <tr>
                            <?php for ($i=0; $i<count($value); $i++) { ?>
                                <td><?php echo htmlspecialchars($value[$i]); ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>