<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li class='active'>Add logical volume</li>
            </ol>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <select id="vg_selector" class="form-control">
                            <option id="default">Select a volume group</option>
                            <?php foreach ($data as $row) { ?>
                                <option data-vg="<?php echo htmlspecialchars($row['VG']); ?>" data-free="<?php echo htmlspecialchars($row['VFree']); ?>"><?php echo htmlspecialchars($row['VG']); ?> | <?php echo htmlspecialchars($row['VFree'])?> GB Free</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row top-buffer" id="input_name_row" hidden>
                    <div class="col-md-12">
                        <input type="text" id="name_input" class="form-control" placeholder="Name..." required>
                    </div>
                </div>
                <div class="row top-buffer" hidden id="size_row">
                    <div class="col-md-6">
                        <input id="size_input" type="text" value="1" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <div id="add_slider" class="slider"></div>
                    </div>
                </div>
                <div class="row top-buffer" hidden id="too_small_row">
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert"><h3 align="center">The volume group has not enough space left!</h3></div>
                    </div>
                </div>
            </div>

            <div class="panel-footer" id="add_lv_panel_footer" hidden>
                <button id="create_volume_button" class="btn btn-success" type='submit'><span class="glyphicon glyphicon-plus"></span> Create</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/lvm/add'],function(methods) {
                methods.slider();
            });
        });
    </script>
</div>