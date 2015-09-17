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
                <li class='active'>Delete</li>
            </ol>
            <div class="panel-body">
                <pre>
                    <?php print_r($data) ?>
                    <!--
                    Add table here
                    Add progress bar for snapshot usage
                    -->
                </pre>
            </div>

            <div class="panel-footer">
                <button id="delete_snapshot_button" class="btn btn-danger" type='submit'><span class="glyphicon glyphicon-trash"></span> Delete</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/lvm/delete_snapshot'], function (methods) {
                methods.delete_snapshot();
            });
        });
    </script>
</div>