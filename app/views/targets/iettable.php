<div id='workspace'>
    <div class="container">
        <div class="row">
            <div class='panel panel-default'>
                <ol class='panel-heading breadcrumb'>
                    <li><a href='#'>Overview</a></li>
                    <li class="active"><?php if ($data['type'] == 'volume') echo 'Volumes'; else if ($data['type'] == 'session') echo 'Sessions' ?></li>
                </ol>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-md-1"><?php if ($data['type'] == 'volume') echo 'Lun'; else if ($data['type'] == 'session') echo 'Session' ?></th>
                            <th class="col-md-2">tid</th>
                            <th class="col-md-2">iqn</th>
                            <th class="col-md-1"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-1"></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach (array_reverse($data['data']) as $key => $row) { ?>
                            <tr class="clickable active" data-toggle="collapse" id="row<?php echo htmlspecialchars($key) ?>"
                                data-target=".row<?php echo htmlspecialchars($key) ?>">
                                <?php if ($data['type'] == 'volume') { ?>
                                    <td class="col-md-1"><?php if (isset($row['lun'])) echo '<a href="#" class="expandrow"><span class="glyphicon glyphicon-15 glyphicon-plus"></span></a>' ?></td>
                                <?php } else if ($data['type'] == 'session') { ?>
                                    <td class="col-md-1"><?php if (isset($row['session'])) echo '<a href="#" class="expandrow"><span class="glyphicon glyphicon-15 glyphicon-plus"></span></a>' ?></td>
                                <?php } ?>
                                <td class="col-md-2"><?php echo $row['tid'] ?></td>
                                <td class="col-md-2"><?php echo $row['iqn'] ?></td>
                                <td class="col-md-1"></td>
                                <td class="col-md-1"></td>
                                <td class="col-md-1"></td>
                                <td class="col-md-1"></td>
                            </tr>
                            <?php if ($data['type'] == 'volume') { ?>
                                <?php if (isset($row['lun'])) { ?>
                                    <tr class="collapse row<?php echo htmlspecialchars($key) ?>">htmlspecialchars(
              )                          <th class="col-md-1">ID</th>
                                        <th class="col-md-1">State</th>
                                        <th class="col-md-1">IOType</th>
                                        <th class="col-md-2">IOMode</th>
                                        <th class="col-md-1">Blocks</th>
                                        <th class="col-md-1">Blocksize</th>
                                        <th class="col-md-4">Path</th>
                                    </tr>
                                    <?php foreach (array_reverse($row['lun']) as $lun) { ?>
                                        <tr class="collapse row<?php echo htmlspecialchars($key) ?>">
                                            <td class="col-md-1"><?php echo $lun['id'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['state'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['iotype'] ?></td>
                                            <td class="col-md-2"><?php echo $lun['iomode'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['blocks'] ?></td>
                                            <td class="col-md-1"><?php echo $lun['blocksize'] ?></td>
                                            <td class="col-md-4"><?php echo $lun['path'] ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else if ($data['type'] == 'session') { ?>
                                <?php if (isset($row['session'])) { ?>
                                    <tr class="collapse row<?php echo htmlspecialchars($key) ?>">
                                        <th class="col-md-1">SID</th>
                                        <th class="col-md-2">CID</th>
                                        <th class="col-md-2">Initiator</th>
                                        <th class="col-md-1">IP</th>
                                        <th class="col-md-1">State</th>
                                        <th class="col-md-1">HD</th>
                                        <th class="col-md-1">DD</th>
                                    </tr>
                                    <?php foreach (array_reverse($row['session']) as $session) { ?>
                                        <tr class="collapse row<?php echo htmlspecialchars($key) ?>">
                                            <td class="col-md-1"><?php echo htmlspecialchars($session['sid']) ?></td>
                                            <td class="col-md-2"><?php echo htmlspecialchars($session['cid']) ?></td>
                                            <td class="col-md-2"><?php echo htmlspecialchars($session['initiator']) ?></td>
                                            <td class="col-md-1"><?php echo htmlspecialchars($session['ip']) ?></td>
                                            <td class="col-md-1"><?php echo htmlspecialchars($session['state']) ?></td>
                                            <td class="col-md-1"><?php echo htmlspecialchars($session['hd']) ?></td>
                                            <td class="col-md-1"><?php echo htmlspecialchars($session['dd']) ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
		require(['common'], function () {
			require(['pages/iettable', 'domReady'], function (methods, domReady) {
				domReady(function () {
					methods.change_expand_row_button();
				});
			});
        });
    </script>
</div>