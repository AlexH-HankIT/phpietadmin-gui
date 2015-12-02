<div id="workspace">
    <div class="container">
        <table id="table"
               data-toolbar="#toolbar"
               data-data-field="body"
               data-show-refresh="true"
               data-search="true"
               data-show-export="true"
               data-show-columns="true"
               data-url="/phpietadmin/overview/disks/json">
            <thead>
            <tr>
                <th data-field="NAME">NAME</th>
                <th data-field="MIN">MIN</th>
                <th data-field="RM">RM</th>
                <th data-field="SIZE">SIZE</th>
                <th data-field="RO">RO</th>
                <th data-field="TYPE">TYPE</th>
                <th data-field="MOUNTPOINT">MOUNTPOINT</th>
            </tr>
            </thead>
        </table>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/diskTable', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.table();
                });
            });
        });
    </script>
</div>