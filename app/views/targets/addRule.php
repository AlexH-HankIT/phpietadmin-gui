<div id="workspace">
    <div class = "container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Add ACL</li>
            </ol>
            <div class="panel-body">
                <button id="addAllowRuleButton" type="button" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Allow</button>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="type" value="initiators" checked="checked">Initiator
                    </label>
                    <label class="btn btn-default">
                        <input type="Radio" name="type" value="targets">Target
                    </label>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped searchabletable">
                    <thead>
                    <tr>
                        <th class="col-md-1"><input id="masterCheckbox" type="checkbox"></th>
                        <th class="col-md-3">Type</th>
                        <th class="col-md-4">Name</th>
                        <th class="col-md-4">Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($data)) { ?>
                        <?php foreach ($data as $value) { ?>
                            <tr>
                                <td class="objectId" hidden><?php echo htmlspecialchars($value['objectid']); ?></td>
                                <td class="col-md-1"><input class="objectCheckbox" type="checkbox"></td>
                                <td class="col-md-3"><?php echo htmlspecialchars($value['type']); ?></td>
                                <td class="col-md-4"><?php echo htmlspecialchars($value['name']); ?></td>
                                <td class="col-md-4"><?php echo htmlspecialchars($value['value']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/target/addRule', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.add_event_handler_addallowrulebutton();
                    methods.enable_filter_table_plugin();
                });
            });
        });
    </script>
</div>