<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li class='active'>Users</li>
            </ol>

            <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Add iSCSI user</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-16">Username</span>
                                        <input type="text" class="form-control" id="addUserUsernameInput" placeholder="Username...">
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group" id="passwordInputGroup">
                                        <span class="input-group-addon input-group-addon-16">Password</span>
                                        <input type="text" class="form-control" id="addUserPasswordInput" placeholder="Password...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" id="generatePasswordButton" type="button">Generate</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div id="showErrorInModal" class="col-md-3"></div>
                                <div class="col-md-5 col-md-offset-4">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                                    <button type="button" class="btn btn-success" id="savePasswordButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="addusertable" class="table searchabletable">
                    <thead>
                    <tr>
                        <th class="col-md-2">Username</th>
                        <th class="col-md-1 center">Copy</th>
                        <th class="col-md-1 center">Show</th>
                        <th class="col-md-4">Password</th>
                        <th class="col-md-1"><button id="addRow" class="btn btn-xs btn-success" data-toggle="modal" data-target="#createUserModal"><span class="glyphicon glyphicon-plus"></span> Add</button></th>
                        <th class="col-md-1"></th>
                    </tr>
                    </thead>
                    <tbody id="addusertablebody">
                    <?php if (is_array($data)) { ?>
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td class="col-md-2 username"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="col-md-1 center"><button class="btn btn-xs btn-warning copyPasswordButton"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Copy</button></td>
                                <td class="col-md-1 center"><input class="showPasswordCheckbox" type="checkbox"></td>
                                <td class="col-md-4"><span class="passwordPlaceholder"><i>Hidden</i></span><span class="password" hidden><?php echo htmlspecialchars($row['password']); ?></span></td>
                                <td class="col-md-1"><button class="btn btn-xs btn-danger deleteuserrow"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</button></td>
                                <td class="col-md-1"></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
		require(['common'], function () {
			require(['pages/ietUserTable', 'domReady'], function (methods, domReady) {
				domReady(function () {
					methods.addEventHandlerPasswordField();
					methods.addEventHandlerDeleteUserRow();
					methods.enableFilterTablePlugin();
                    methods.addUserModal();
				});
			});
		});
    </script>
</div>