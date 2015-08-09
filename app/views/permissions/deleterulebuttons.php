<div class="workspacedirect">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete ACL</li>
            </ol>

            <div class='panel-body'>
                <button id="deleterulebutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
                </button>

                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" name="deleteruletype" value="initiators.allow" checked="checked">Initiators
                    </label>

                    <label class="btn btn-default">
                        <input type="Radio" name="deleteruletype" value="targets.allow"> Targets
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteruleworkspace"></div>

<script>
    require(['common'],function() {
        require(['pages/deleterule'],function(methods) {
            methods.add_event_handler_deleteruletype();
            methods.add_event_handler_deleterulebutton();
            methods.enable_filter_table_plugin();
            methods.load_default_table();
        });
    });
</script>