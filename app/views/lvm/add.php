<div class="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li class='active'>Add logical volume</li>
            </ol>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <select id="vg_selector" data-live-search="true" data-width="100%" title="Select a volume group..." data-size="10">
                            <?php foreach ($data as $row) { ?>
                                <option data-subtext="<?php echo htmlspecialchars($row['VFree'])?> GB Free" data-free="<?php echo htmlspecialchars($row['VFree']); ?>"><?php echo htmlspecialchars($row['VG']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row top-buffer" id="input_name_row" hidden>
                    <div class="col-md-12">
                        <input type="text" id="name_input" class="form-control" placeholder="Name..." required>
                    </div>
                </div>
                <div class="size_row row top-buffer hidden-xs" hidden>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" value="1" class="form-control size_input" required>
                    </div>
                    <div class="col-md-6">
                        <div class="slider add_slider"></div>
                    </div>
                </div>
                <div class="row visible-xs" hidden>
                    <div class="col-xs-12">
                        <input type="text" value="1" class="form-control size_input" required>
                    </div>
                </div>
                <div class="row visible-xs" hidden>
                    <div class="col-xs-12">
                        <div class="slider add_slider"></div>
                    </div>
                </div>
                <div class="row top-buffer" hidden id="too_small_row">
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert"><h3 class="text-center">The volume group has not enough space left!</h3></div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="add_lv_panel_footer" hidden>
                <button id="create_volume_button" class="btn btn-success has-spinner" type='submit' data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Creating...'>
                    <span class="glyphicon glyphicon-plus"></span> Create
                </button>
            </div>
        </div>
    </div>
    <script>
        require(['common'],function() {
            require(['pages/lvm/add', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.slider();
                });
            });
        });
    </script>
</div>