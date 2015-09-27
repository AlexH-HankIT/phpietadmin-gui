<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li class='active'>Config</li>
            </ol>

            <div id="config-menu" class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Value</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($value['optioningui']); ?></td>
                                <td>
                                    <input size="80" type="text" name="fname" value="<?php echo htmlspecialchars($value['value']); ?>" disabled>
                                    <a href="#<?php echo htmlspecialchars($value['option']); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <span class="label label-success bestaetigung">Success</span>
                                </td>
                                <td><?php if (isset($value['description'])) echo htmlspecialchars($value['description']) ?></td>
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