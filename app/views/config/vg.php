<div class="workspacedirect">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <table id="table1" class="table table-bordered connectedSortable">
                    <tbody class="content">
                        <tr class="ui-state-default"><td>VG_data01</td></tr>
                        <tr class="ui-state-default"><td>VG_data02</td></tr>
                        <tr class="ui-state-default"><td>VG_data03</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="table2" class="table table-bordered connectedSortable">
                    <tbody class="content">
                        <tr class="ui-state-default"><td>VG_data04</td></tr>
                        <tr class="ui-state-default"><td>VG_data05</td></tr>
                        <tr class="ui-state-default"><td>VG_data06</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/config/vg'], function(methods) {
                methods.drag_drop();
            });
        });
    </script>
</div>