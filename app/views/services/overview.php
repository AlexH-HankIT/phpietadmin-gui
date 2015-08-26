<div id="workspace">
    <div class = 'container'>
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Service</a></li>
                <li class='active'>Overview</li>
            </ol>

            <div class="table-responsive">
                <table class='table table-striped'>
                    <thead>
                    <tr>
                        <th><span class='glyphicon glyphicon-tags'></span></th>
                        <th>Status</th>
                        <th class='centered-table-field'>Start</th>
                        <th class='centered-table-field'>Stop</th>
                        <th class='centered-table-field'>Restart</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr class='servicerow'>
                            <td class='servicename col-md-6'><?php echo htmlspecialchars($row['name']) ?></td>
                            <td class='col-md-3'><span class='servicestatus'></span></td>
                            <td class='col-md-1 centered-table-field'><a href='#' class='servicestart'><span class='glyphicon glyphicon-play glyphicon-20 '></a></td>
                            <td class='col-md-1 centered-table-field'><a href='#' class='servicestop'><span class='glyphicon glyphicon-stop glyphicon-20'></a></td>
                            <td class='col-md-1 centered-table-field'><a href='#' class='servicerestart'><span class='glyphicon glyphicon-repeat glyphicon-20'></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <script>
            require(['common'],function() {
                require(['pages/servicesoverview'],function(methods) {
                    methods.set_service_status();
                    methods.add_event_handler_servicestart();
                    methods.add_event_handler_servicestop();
                    methods.add_event_handler_servicerestart();
                });
            });
        </script>
</div>

