<div id="workspace">
    <div class='container'>
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li class='active'>Objects</li>
            </ol>
            <div class="modal fade" id="addObjectModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Add object</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <select id="addObjectModalTypeSelect" class="form-control">
                                            <option value="hostv4">IPv4 Host</option>
                                            <option value="hostv6">IPv6 Host</option>
                                            <option value="networkv4">IPv4 Network</option>
                                            <option value="networkv6">IPv6 Network</option>
                                            <option value="iqn">IQN</option>
                                            <option value="all">ALL</option>
                                            <option value="regex">Regex</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Name</span>
                                        <input class="form-control" id="addObjectModalNameInput" type="text" placeholder="Meaningful name...">
                                        <span hidden class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Value</span>
                                        <input class="form-control" id="addObjectModalValueInput" type="text" placeholder="Your value...">
                                        <span hidden class="input-group-addon"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-md-3">
                                    <span id="showErrorInModal" class="label label-danger"></span>
                                </div>
                                <div class="col-md-5 col-md-offset-7">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                                    <button type="button" class="btn btn-success" id="saveObjectButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class='table searchabletable'>
                    <thead>
                    <tr>
                        <th class='col-md-2'>Type</th>
                        <th class='col-md-4'>Name</th>
                        <th class='col-md-4'>Value</th>
                        <th class="col-md-1"><button class="btn btn-xs btn-success" data-toggle="modal" data-target="#addObjectModal"><span class="glyphicon glyphicon-plus"></span> Add</button></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (is_array($data['objects'])) { ?>
                        <?php foreach ($data['objects'] as $objects) { ?>
                            <tr>
                                <td hidden class='id'><?php echo htmlspecialchars($objects['objectid']); ?></td>
                                <td class='col-md-2 objectType'><?php echo htmlspecialchars($objects['type']); ?></td>
                                <td class='col-md-4 objectName'><?php echo htmlspecialchars($objects['name']); ?></td>
                                <td class='col-md-4 objectValue'><?php echo htmlspecialchars($objects['value']); ?></td>
                                <td class="col-md-1"><button class="btn btn-xs btn-danger objectDelete"><span class="glyphicon glyphicon-remove"></span> Delete</button></td>
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
            require(['pages/objecttable', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.addObjectModal();
                    methods.addEventHandlerDeleteObject();
                    methods.enableFilterTablePlugin();
                });
            });
        });
    </script>
</div>