<div class="workspacedirect">
    <div class="container">
        <ol class="breadcrumb">
            <li class="active"><?php echo 'Config' ?></li>
        </ol>

        <div id="config-menu">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <?php foreach ($data as $value) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($value[0]); ?></td>
                                <td>
                                    <input size="80" type="text" name="fname" value="<?php echo htmlspecialchars($value[1]); ?>" disabled>
                                    <a href="#<?php echo htmlspecialchars($value[0]); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <span class="label label-success bestaetigung">Success</span>
                                </td>
                            <td><?php if (isset($value[2])) { echo htmlspecialchars($value[2]); } ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/configtable'], function(methods) {
                methods.add_event_handler_config();
            });
        });
    </script>
</div>