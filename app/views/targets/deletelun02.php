<p>
    <select name="deletelunlunselection" id="lun" class="form-control">
        <option value="default" id="default">Select lun</option>
        <?php foreach ($data as $value) { ?>
            <option value="<?php echo $value['path'] ?>"><?php echo $value['lun'] . ' ' .$value['path'] ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <input class="btn btn-danger" type="submit" value="Delete" onclick="">
</p>