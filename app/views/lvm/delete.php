<div class="workspacedirect">
    <div class = "container">
        <ol class="breadcrumb">
            <li class="active">Delete logical volumes</li>
        </ol>

        <div class="jumbotron">
            <div class="row">
                <select id="logicalvolumedeleteselection" class="form-control">
                    <option id="default">Select...</option>
                    <?php foreach ($data as $row) { ?>
                        <option
                            value="<?php echo htmlspecialchars($row); ?>"> <?php echo htmlspecialchars($row); ?> </option>
                    <?php } ?>
                </select>
            </div>
            <div class="row top-buffer">
                <button id="logicalvolumedeletebutton" class="btn btn-danger" type="submit">
                    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
                </button>
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