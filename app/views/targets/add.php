<div id="leftDiv">
    <form method="post">
        <p>Name: <input type="text" name="name" id="name"/> Name to be added to the iqn</p>
        <p>
            LV:
            <select name="path" id="path">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
        </p>
        <p><input type="submit" value="Create" onclick="return validatetargetadd();"/></p>
    </form>
</div>