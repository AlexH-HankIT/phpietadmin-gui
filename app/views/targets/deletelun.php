<div class="container">
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-10">
                <select id="deletelunlunselection" class="form-control">
                    <?php foreach ($data as $value) { ?>
                        <option id="lun" value="<?php echo htmlspecialchars($value['lun']) ?>"><?php echo htmlspecialchars($value['lun']) . ' ' . htmlspecialchars($value['path']); ?></option>
                        <option id="path" style="display:none;" value="<?php echo htmlspecialchars($value['path']); ?>"></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-offset-1 col-md-1">
                <button id="deletelunbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    require(['common'],function() {
        require(['pages/deletelun']);
    });
</script>