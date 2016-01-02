<?php if ($data['lv'][0]['Attr'][5] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The
        selected logical volume is in use!
    </div>
<?php } ?>
<div class="table-responsive">
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
                <td><?php echo htmlspecialchars($lv['VG']) ?></td>
                <td><?php echo htmlspecialchars($lv['Attr']) ?></td>
                <td><?php echo htmlspecialchars($lv['LSize']) ?></td>
                <td><?php echo htmlspecialchars($lv['Pool']) ?></td>
                <td><?php echo htmlspecialchars($lv['Origin']) ?></td>
                <td><?php echo htmlspecialchars($lv['Data%']) ?></td>
                <td><?php echo htmlspecialchars($lv['Move']) ?></td>
                <td><?php echo htmlspecialchars($lv['Log']) ?></td>
                <td><?php echo htmlspecialchars($lv['Cpy%Sync']) ?></td>
                <td><?php echo htmlspecialchars($lv['Convert']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<button class="delete_snapshot btn btn-danger" type='submit' data-loading-text="Deleting..."><span class="glyphicon glyphicon-trash"></span>
    Delete
</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/delete_snapshot', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.delete_snapshot();
            });
        });
    });
</script>
