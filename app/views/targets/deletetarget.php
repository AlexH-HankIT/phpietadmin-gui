<div class="workspacedirect">
    <div class="container">
        <div class="jumbotron">
            <div class="row">
                <label>
                    <input type="radio" name="lundeletion" checked/>
                    Detach LUN(s)
                </label>
                (No data will be deleted)
            </div>
            <div class="row top-buffer">
                <label>
                    <input type="radio" name="lundeletion"/>
                    Delete attached LUN(s)
                </label>
                (LVM only, data will be deleted!)
            </div>
            <div class="row top-buffer">
                <label>
                    <input type="checkbox" checked/>
                    Delete acl
                </label>
                from initiator allow and target allow
            </div>
            <div class="row top-buffer">
                <label>
                    <input type="checkbox" />
                    Force
                </label>
                (Delete even if in use, requires 'Delete allow rules')
            </div>
            <div class="row top-buffer">
                <button id="deletetargetbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/deletetarget'],function(methods) {
                methods.add_qtip_deletetargetbutton();
                methods.add_event_handler_deletetargetbutton();
            });
        });
    </script>
</div>