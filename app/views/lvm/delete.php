<div id="workspace">
    <div class = "container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li class='active'>Delete logical volume</li>
            </ol>

            <div class="panel-body">
                <select id="logicalvolumedeleteselection" class="form-control">
                    <option id="default">Select...</option>
                    <?php foreach ($data as $row) { ?>
                        <option value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div class="panel-footer">
                <button id="logicalvolumedeletebutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
            </div>
        </div>

    <script>
        require(['common'],function() {
            require(['pages/lvmdelete'],function(methods) {
                methods.add_qtip_logicalvolumedeletebutton();
                methods.add_event_handler_logicalvolumedeletebutton();
            });
        });
    </script>
</div>