<div class="workspacedirect">
    <div class = "container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li class='active'>Add logical volume</li>
            </ol>

            <div class="panel-body">
                <select id="vgselection" multiple class="form-control">
                    <?php foreach ($data as $row) { ?>
                        <option value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div id="lv"></div>

    <script>
        require(['common'],function() {
            require(['pages/vginput'],function(methods) {
                methods.add_event_handler_vgselection();
            });
        });
    </script>
</div>