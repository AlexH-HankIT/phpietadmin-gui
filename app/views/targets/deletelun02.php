<!-- pulled via ajax -->
<p>
    <select id="deletelunlunselection" class="form-control">
        <?php foreach ($data as $value) { ?>
            <option id="lun" value="<?php echo htmlspecialchars($value['lun']) ?>"><?php echo htmlspecialchars($value['lun']) . ' ' . htmlspecialchars($value['path']); ?></option>
            <option id="path" style="display:none;" value="<?php echo htmlspecialchars($value['path']); ?>"></option>
        <?php } ?>
    </select>
</p>
<button id="deletelunbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>