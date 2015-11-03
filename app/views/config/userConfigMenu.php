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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">New password</span>
                                        <input type="password" class="form-control" id="inputPassword" placeholder="New password...">
                                    </div>
                                </div>
                            </div>
                            <div class="row top-buffer">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon input-group-addon-25">Repeat password</span>
                                        <input type="password" class="form-control" id="inputPasswordRepeat" placeholder="Repeat password...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Close</button>
                            <button type="button" class="btn btn-success" id="savePasswordButton"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table white-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Edit password</th>
                            <th>Delete user</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($data as $user) { ?>
                        <tr>
                            <td><?php echo $user['username'] ?></td>
                            <td><a href='#'><span class="glyphicon glyphicon-pencil glyphicon-15" data-toggle="modal" data-target="#editPasswordModal"></span></a></td>
                            <td><a href='#'><span class="glyphicon glyphicon-remove glyphicon-15"></span></a></td>
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
                    methods.model();
                    methods.table();
            	});
            });
        });
    </script>
</div>