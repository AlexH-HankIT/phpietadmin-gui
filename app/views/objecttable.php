<div class='workspacedirect'>
    <div class='container'>
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li class='active'>Objects</li>
            </ol>

            <div class="table-responsive">
                <table id='objectstable' class='table searchabletable'>
                    <thead>
                    <tr>
                        <th class='col-md-2'>Type</th>
                        <th class='col-md-4'>Name</th>
                        <th class='col-md-4'>Value</th>
                        <th class='col-md-1'><a href='#' id='addobjectrowbutton'><span class='glyphicon glyphicon-plus glyphicon-20' aria-hidden='true'></span></a></th>
                        <th class='col-md-1'></th>
                    </tr>
                    </thead>

                    <tbody id='addobjectstbody'>
                    <?php if (is_array($data['objects'])) { ?>
                        <tr hidden id="template">
                            <td class='col-md-2'>
                                <select class="typeselection">
                                    <option class="default">Select type...</option>
                                    <option value="hostv4">IPv4 Host</option>
                                    <option value="hostv6">IPv6 Host</option>
                                    <option value="networkv4">IPv4 Network</option>
                                    <option value="networkv6">IPv6 Network</option>
                                    <option value="iqn">IQN</option>
                                    <option value="all">ALL</option>
                                    <option value="regex">Regex</option>
                                </select>
                                <span class="label label-success bestaetigung">Success</span>
                            </td>
                            <td class='col-md-4'>
                                <input class="objectname" type="text" name="value" placeholder="Meaningful name...">
                                <span class="label label-success bestaetigung">Success</span>
                            </td>
                            <td class='col-md-4'>
                                <input class="objectvalue" type="text" name="value" placeholder="Your value...">
                                <span class="label label-success bestaetigung">Success</span>
                            </td>
                            <td class='col-md-1'><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
                            <td class='col-md-1'>
                                <a href="#" class="saveobjectrow"><span class="glyphicon glyphicon-save glyphicon-20" aria-hidden="true"></span></a>
                                <span class="label label-success bestaetigung">Success</span>
                            </td>
                        </tr>
                        <?php foreach ($data['objects'] as $objects) { ?>
                            <tr>
                                <td hidden class='id'><?php echo htmlspecialchars($objects['objectid']); ?></td>
                                <td class='col-md-2 type'><?php echo htmlspecialchars($objects['type']); ?></td>
                                <td class='col-md-4 objectname'><?php echo htmlspecialchars($objects['name']); ?></td>
                                <td class='col-md-4 objectvalue'><?php echo htmlspecialchars($objects['value']); ?></td>
                                <td class='col-md-1'><a href='#' class='deleteobjectrow'><span class='glyphicon glyphicon-trash glyphicon-20' aria-hidden='true'></span></a></td>
                                <td class='col-md-1'></td>
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
            require(['pages/objecttable'],function(methods) {
                methods.add_event_handler_addobjectrowbutton();
                methods.add_event_handler_typeselection();
                methods.add_event_handler_saveobjectrow();
                methods.add_event_handler_objectvalue();
                methods.add_event_handler_objectname();
                methods.add_event_handler_deleteobjectrow();
            });
        });
    </script>
</div>