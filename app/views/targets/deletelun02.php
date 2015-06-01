<p>
    <select id="deletelunlunselection" class="form-control">
        <?php foreach ($data as $value) { ?>
            <option id="lun" value="<?php echo htmlspecialchars($value['lun']) ?>"><?php echo htmlspecialchars($value['lun']) . ' ' . htmlspecialchars($value['path']); ?></option>
            <option id="path" style="display:none;" value="<?php echo htmlspecialchars($value['path']); ?>"></option>
        <?php } ?>
    </select>
</p>
<p>
    <input class="btn btn-danger" type="submit" value="Delete" onclick="send_deletelun()">
</p>