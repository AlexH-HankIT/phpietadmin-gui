<div id="configure_target_body">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete ACL</li>
            </ol>

            <div class='panel-body'>
                <button id="delete_rule_button" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
                </button>

                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="radio" name="delete_rule_type" value="initiators" checked="checked">Initiators
                    </label>

                    <label class="btn btn-default">
                        <input type="radio" name="delete_rule_type" value="targets"> Targets
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div id="data"></div>

    <script>
        require(['common'],function() {
            require(['pages/target/delete_rule_control', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.add_event_handler_deleterulebutton();
                    methods.enable_filter_table_plugin();
                    methods.load_data('initiators');
                    methods.toggle_checkboxes();
                    methods.add_event_handler_delete_rule_type();
                });
            });
        });
    </script>