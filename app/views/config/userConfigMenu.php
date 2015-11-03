<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Config</a></li>
                <li class='active'>User</li>
            </ol>

            <div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title" id="myModalLabel">Edit password</h3>
                        </div>
                        <div class="modal-body">
                            <input hidden id="savedUsername">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">New password</span>
                                        <input type="password" class="form-control passwordInput" id="inputPassword" placeholder="New password...">
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">Repeat password</span>
                                        <input type="password" class="form-control passwordInput" id="inputPasswordRepeat" placeholder="Repeat password...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div id="showErrorInModal" class="col-md-5"></div>

                                <div class="col-md-5 col-md-offset-2">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                                    <button type="button" class="btn btn-success" id="savePasswordButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title" id="myModalLabel">Create user</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">Username</span>
                                        <input type="text" class="form-control" id="usernameNew" placeholder="Username...">
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">Password</span>
                                        <input type="password" class="passwordInputCreateUser form-control" id="inputPasswordNew" placeholder="Password...">
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">Repeat password</span>
                                        <input type="password" class="passwordInputCreateUser form-control" id="inputPasswordRepeatNew" placeholder="Repeat password...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div id="showErrorInCreateUserModal" class="col-md-5"></div>
                                <div class="col-md-5 col-md-offset-2">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                                    <button type="button" class="btn btn-success" id="saveUserButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table white-table" id="userTable">
                    <thead>
                        <tr>
                            <th class="col-md-10">Username</th>
                            <th class="col-md-1"><button id="addRow" class="btn btn-xs btn-success" data-toggle="modal" data-target="#createUserModal"><span class="glyphicon glyphicon-plus"></span> Add</button></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($data as $user) { ?>
                        <tr>
                            <td class="col-md-10 username"><?php echo $user['username'] ?></td>
                            <td class="col-md-1"><button class="btn btn-xs btn-warning editPasswordSpan" data-toggle="modal" data-target="#editPasswordModal"><span class="glyphicon glyphicon-pencil"></span> Edit</button></td>
                            <td class="col-md-1"><button class="btn btn-xs btn-danger deleteUserSpan"><span class="glyphicon glyphicon-remove"></span> Delete</button></td>
                        </tr>
					<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/config/userConfigMenu', 'domReady'], function(methods, domReady) {
				domReady(function () {
                    methods.editPasswordModel();
                    methods.addUserModal();
                    methods.table();
            	});
            });
        });
    </script>
</div>