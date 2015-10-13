<div id="workspace">
    <div class = 'container'>
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Service</a></li>
                <li class='active'>Add</li>
            </ol>

            <div class="table-responsive">
                <table class='table table-striped'>
                    <thead>
                    <tr>
                        <th><span class='glyphicon glyphicon-ok glyphicon-20'></span></th>
                        <th>Service</th>
                        <th></th>
                        <th></th>
                        <th><a id='addservice' href='#'><span class='glyphicon glyphicon-plus glyphicon-20'></span></a></th>
                    </tr>
                    </thead>
                    <tbody id='addservicetablebody'>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td class='col-md-2'><input class='serviceenabled' type='checkbox' <?php if ($row['enabled'] == 1) echo 'checked' ?>> <span class='label bestaetigung label-success serviceenabledspan'>Success</span></td>
                            <td class='col-md-8'><input class='serviceinput' type='text' value="<?php echo htmlspecialchars($row['name']) ?>" disabled> <span class='label bestaetigung label-success serviceinputspan'>Success</span></td>
                            <td><input class='serviceinputoldvalue' type='text' value="<?php echo htmlspecialchars($row['name']) ?>" hidden></td>
                            <td class='col-md-1'><a class='editservice' href='#'><span class='glyphicon glyphicon-pencil glyphicon-20'></span></a></td>
                            <td class='col-md-1'><a class='deleteservice' href='#'><span class='glyphicon glyphicon-trash glyphicon-20'></span></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        require(['common'],function() {
            require(['pages/services/add', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.add_event_handler_addservice();
                    methods.add_event_handler_deleteservicebutton();
                    methods.add_event_handler_editservicebutton();
                    methods.add_event_handler_serviceenabled();
                });
            });
        });
    </script>
</div>