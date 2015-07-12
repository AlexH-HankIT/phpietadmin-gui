<div class="workspacedirect">
    <div class = "container">
        <div class="row">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table id="objectstable" class="table table-striped searchabletable">
                        <thead>
                            <tr>
                                <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                                <th class="col-md-3">Type</th>
                                <th class="col-md-4">Name</th>
                                <th class="col-md-4">Value</th>
                            </tr>
                        </thead>
                        <tbody id="objectselection">
                        <?php if (is_array($data['objects'])) { ?>
                            <?php foreach ($data['objects'] as $value ) { ?>
                                <tr>
                                    <td class="objectid" hidden><?php echo htmlspecialchars($value['objectid']); ?></td>
                                    <td class="col-md-1"><input class="objectcheckbox" type="checkbox"/></td>
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
            <div class="col-md-2">
                <div id="addallowrulebutton">
                    <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Allow</button>
                </div>
                <div class="btn-group top-buffer" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" id="initiatorcheckbox" name="type" value="initiator" checked="checked"/>Initiator
                    </label>
                    <label class="btn btn-default">
                        <input id="targetcheckbox" type="Radio" name="type" value="target"/> Target
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/addrule'],function(methods) {
                methods.add_event_handler_addallowrulebutton();
            });
        });
    </script>
</div>