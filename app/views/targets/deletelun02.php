<p>
    <select id="deletelunlunselection" class="form-control">
        <?php foreach ($data as $value) { ?>
            <option id="lun" value="<?php echo $value['lun'] ?>"><?php echo $value['lun'] . ' ' .$value['path'] ?></option>
            <option id="path" style="display:none;" value="<?php echo $value['path'] ?>"></option>
        <?php } ?>
    </select>
</p>
<p>
    <input class="btn btn-danger" type="submit" value="Delete" onclick="send_deletelun()">
</p>