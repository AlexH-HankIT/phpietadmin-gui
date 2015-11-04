<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li class='active'>Users</li>
            </ol>

            <table hidden>
                <tr id="template">
                    <td class="col-md-5 usernamecell">
                        <input class="username" type="text" placeholder="Username">
                        <span class="label label-success bestaetigung">Success</span>
                    </td>
                    <td class="col-md-5 passwordcell">
                        <a href="#"><button class="btn btn-xs btn-success generate_pw">Generate</button></a>
                        <input class="password" maxlength="16" type="text" placeholder="Password">
                        <span class="label label-success bestaetigung">Success</span>
                    </td>
                    <td class="col-md-1"><a href="#" class="deleteuserrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
                    <td class="col-md-1"><a href="#" class="saveuserrow"><span class="glyphicon glyphicon-save glyphicon-20" aria-hidden="true"></span></a></td>
                </tr>
            </table>

            <div class="table-responsive">
                <table id="addusertable" class="table searchabletable">
                    <thead>
                    <tr>
                        <th class="col-md-5">Username</th>
                        <th class="col-md-5">Password</th>
                        <th class="col-md-1"><a href="#" id="adduserrowbutton"><span class="glyphicon glyphicon-plus glyphicon-20" aria-hidden="true"></span></a></th>
                        <th class="col-md-1"></th>
                    </tr>
                    </thead>
                    <tbody id="addusertablebody">
                    <?php if (is_array($data)) { ?>
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td class="col-md-5 username"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="col-md-5"><span class="passwordfield"><span class="passwordfieldplaceholder"><i>Show</i></span><span class="password" hidden><?php echo htmlspecialchars($row['password']); ?></span></span></td>
                                <td class="col-md-1"><a href="#" class="deleteuserrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
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
			require(['pages/usertable', 'domReady'], function (methods, domReady) {
				domReady(function () {
					methods.add_event_handler_passwordfield();
					methods.add_event_handler_adduserrowbutton();
					methods.add_event_handler_deleteuserrow();
					methods.enable_filter_table_plugin();
				});
			});
		});
    </script>
</div>