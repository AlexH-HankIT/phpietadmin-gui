<div id="configure_lvm_body">
    <div class="container">
        <?php if ($data['lv'][0]['Attr'][6] === 'o') {?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume is in use!</div>
        <?php } ?>

        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li><a href='#'>Configure</a></li>
                <li><a href='#'>Snapshot</a></li>
                <li class='active'>Add</li>
            </ol>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class='input-group'>
                            <span class='input-group-addon' id='basic-addon1'>Name: </span>
                            <input id="add_snapshot_name_input" type="text" value="Logical volume name + timestamp" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-6">
                        <input id="add_snapshot_size_input" type="text" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <div data-max="<?php echo $data['vg'][0]['VFree'] - 1 ?>" id="add_snapshot_size_slider" class="slider"></div>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <button id="create_snapshot" class="btn btn-success" type='submit'><span class="glyphicon glyphicon-camera"></span> Shoot it</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/lvm/add_snapshot'], function (methods) {
                methods.add_snapshot();
            });
        });
    </script>
</div>