<div class="workspacedirect">
    <div class="container">
        <ol class="breadcrumb">
            <li class="active">Target settings</li>
        </ol>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-9">
                    <select id="targetsettings" class="form-control">
                        <option id="default">Select a target to configure</option>
                        <?php foreach ($data['targets'] as $value) { ?>
                            <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1 col-md-offset-1">
                    <button id="savesettingsbutton" class="btn btn-success" type="submit"><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="settingstable"></div>

    <script>
        require(['common'],function() {
            require(['pages/settings'],function(methods) {
                methods.add_event_handler_targetsettings();
                methods.add_event_handler_savesettingsbutton();
            });
            require(['pages/settingstableqtip'],function(methods) {
                methods.add_qtip();
            });
        });
    </script>
</div>