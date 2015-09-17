<div id="configure_lvm_body">
    <div class="container">
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> You have to resize your filesystem before shrinking otherwise you will loose data!</div>

        <?php if ($data['lv'][0]['Attr'][6] === 'o') {?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume is in use!</div>
        <?php } ?>

        <?php if ($data['lv'][0]['Attr'][0] === 'o') {?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume has snapshots!</div>
        <?php } ?>

        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Shrink</li>
            </ol>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <input id="shrink_input" type="text" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <div data-max="<?php echo $data['lv'][0]['LSize'] ?>"
                             id="shrink_slider" class="slider"></div>
                    </div>
                </div>
            </div>

            <div class='panel-footer'>
                <button id="shrink_volume_button" class="btn btn-warning" type='submit'><span class="glyphicon glyphicon-minus"></span> Shrink</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/lvm/shrink'], function (methods) {
                methods.shrink();
            });
        });
    </script>
</div>