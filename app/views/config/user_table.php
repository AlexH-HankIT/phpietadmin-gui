<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Config</a></li>
                <li class='active'>User</li>
            </ol>

            <div class="table-responsive">
                <table class="table white-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Repeat</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($data as $user) { ?>
                        <tr>
                            <td><?php echo $user['username'] ?></td>
                            <td><input class="password1" disabled type="password" value="           "><span class="label label-success bestaetigung">Success</span></td>
                            <td><input class="password2" disabled type="password" value="           "><span class="label label-success bestaetigung">Success</span></td>
                            <td><a href='#'><span id="editpassword" class="glyphicon glyphicon-pencil glyphicon-15"></span></a></td>
                            <td><a id="savepassworda" hidden href='#'><span id="savepassword" class="glyphicon glyphicon-ok glyphicon-15"></span></a></td>
                        </tr>
					<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/config/configuser', 'domReady'], function(methods, domReady) {
				domReady(function () {
					methods.add_event_handler_editpassword();
					methods.add_event_handler_savepassword();
            	});
            });
        });
    </script>
</div>