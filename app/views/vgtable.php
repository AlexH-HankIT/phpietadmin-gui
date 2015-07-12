<div class="workspacedirect">
    <div class = "container">
        <ol class="breadcrumb">
            <li class="active"><?php echo htmlspecialchars($data['title']); ?></li>
        </ol>

        <div class="table-responsive">
            <table class = "table table-striped">
                <thead>
                <tr>
                    <?php foreach ($data[0] as $value) { ?>
                        <th><?php echo htmlspecialchars($value); ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data[1] as $value) { ?>
                    <tr class='vgrow'>
                        <td class='vgname'><?php echo htmlspecialchars($value[0]); ?></td>
                        <td><a class='vgtablegraph' href='#'><span class='glyphicon glyphicon-picture glyphicon-20'></span></a></td>
                        <td><?php echo htmlspecialchars($value[1]); ?></td>
                        <td><?php echo htmlspecialchars($value[2]); ?></td>
                        <td><?php echo htmlspecialchars($value[3]); ?></td>
                        <td><?php echo htmlspecialchars($value[4]); ?></td>
                        <td><?php echo htmlspecialchars($value[5]); ?></td>
                        <td><?php echo htmlspecialchars($value[6]); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="test" class="chart"></div>

    <script>
        require(['common'],function() {
            require(['pages/vg'],function(methods) {
                methods.add_chart();
            });
        });
    </script>
</div>