<div class="workspace">
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
                                    <?php if($value['field'] === 'input') { ?>
                                        <input size="60" type="text" name="fname" value="<?php echo htmlspecialchars($value['value']); ?>" disabled>
                                        <button data-target="<?php echo htmlspecialchars($value['option']); ?>" class="btn btn-primary btn-xs">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <span>Edit</span>
                                        </button>
                                    <?php } else if ($value['field'] === 'select') { ?>
                                        <?php if ($value['type'] === 'bool') { ?>
                                            <select>
                                                <?php if($value['value'] === 0) { ?>
                                                    <option>Disabled</option>
                                                    <option>Enabled</option>
                                                <?php } else if ($value['value'] === 1) { ?>
                                                    <option>Enabled</option>
                                                    <option>Disabled</option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    <?php } ?>
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
            require(['pages/config/configtable', 'domReady'], function(methods, domReady) {
                domReady(function () {
                    methods.addEventHandlerConfigMenu();
                });
            });
        });
    </script>
</div>