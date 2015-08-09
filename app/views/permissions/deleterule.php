<div class="workspacedirect">
    <div class="container">
        <!-- row open -->
        <div class="row">
            <?php if (isset($data['deleteruleobjectstable']) && !empty($data['deleteruleobjectstable'])) { ?>
                <div class="col-md-6">
                    <div class='panel panel-default'>
                        <ol class='panel-heading breadcrumb'>
                            <li class='active'>Objects</li>
                        </ol>
                        <div class="table-responsive">
                            <table class="table table-striped searchabletable" id="deleteruleobjectstable">
                                <thead>
                                <tr>
                                    <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span>
                                    </th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data['deleteruleobjectstable'] as $row) { ?>
                                    <tr>
                                        <td><input class="objectdeletecheckbox" type="checkbox"></td>
                                        <td class="objectid" hidden><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td class="objectvalue"><?php echo htmlspecialchars($row['value']); ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php } else { ?>
                <div class="col-md-6">
                    <!-- If this span with the value false is found, jquery knows there are no objects available-->
                    <span id="noobjects" hidden>false</span>

                    <div class='panel panel-default'>
                        <ol class='panel-heading breadcrumb'>
                            <li class='active'>Objects</li>
                        </ol>

                        <h4 align="center">No objects</h4>
                    </div>
                </div>

            <?php } ?>

            <?php if (isset($data['deleteruleorphanedtable']) && !empty($data['deleteruleorphanedtable'])) { ?>
                <div class="col-md-6">
                    <div class='panel panel-default'>
                        <ol class='panel-heading breadcrumb'>
                            <li class='active'>Orphans (No object available)</li>
                        </ol>
                        <div class="table-responsive">
                            <table class="table table-striped searchabletable" id="deleteruleorphanedtable">
                                <thead>
                                <tr>
                                    <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span>
                                    </th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data['deleteruleorphanedtable'] as $row) { ?>
                                    <tr>
                                        <td><input class="objectdeletecheckbox" type="checkbox"></td>
                                        <td class="objectvalue"><?php echo htmlspecialchars($row); ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-6">
                    <!-- If this span with the value false is found, jquery knows there are no orphans available-->
                    <span id="noorphans" hidden>false</span>

                    <div class='panel panel-default'>
                        <ol class='panel-heading breadcrumb'>
                            <li class='active'>Orphans (No object available)</li>
                        </ol>

                        <h4 align="center">No orphans</h4>
                    </div>
                </div>
            <?php } ?>
        <!-- row close -->
        </div>
    </div>
</div>