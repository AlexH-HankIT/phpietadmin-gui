<div id="configure_lvm_body">
    <div class="container">

        <?php if ($data['lv'][0]['Attr'][5] === 'o') {?>
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
                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="master_checkbox"></th>
                        <th>LV</th>
                        <th>VG</th>
                        <th>Attr</th>
                        <th>LSize</th>
                        <th>Pool</th>
                        <th>Origin</th>
                        <th>Data%</th>
                        <th>Move</th>
                        <th>Log</th>
                        <th>Cpy%Sync</th>
                        <th>Convert</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['lv'] as $lv) { ?>
                        <tr>
                            <td><input type="checkbox" class="delete_snapshot checkbox"></td>
                            <td class="delete_snapshot lv_name"><?php echo $lv['LV'] ?></td>
                            <td><?php echo $lv['VG'] ?></td>
                            <td><?php echo $lv['Attr'] ?></td>
                            <td><?php echo $lv['LSize'] ?></td>
                            <td><?php echo $lv['Pool'] ?></td>
                            <td><?php echo $lv['Origin'] ?></td>
                            <td><?php echo $lv['Data%'] ?></td>
                            <td><?php echo $lv['Move'] ?></td>
                            <td><?php echo $lv['Log'] ?></td>
                            <td><?php echo $lv['Cpy%Sync'] ?></td>
                            <td><?php echo $lv['Convert'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                <button class="delete_snapshot btn btn-danger" type='submit'><span class="glyphicon glyphicon-trash"></span> Delete</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/lvm/delete_snapshot', 'domReady'], function (methods, domReady) {
                domReady(function () {
                    methods.delete_snapshot();
                });
            });
        });
    </script>
</div>