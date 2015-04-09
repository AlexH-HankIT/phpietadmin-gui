<div id="leftDiv">
    <form method="post">
        <p>Name: <input type="text" name="name" /> Name to be added to the iqn</p>
        <p>
            LV:
            <select name="path">
                <option value="">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
        </p>
        <p><input type="submit" value="Create"/></p>
    </form>
</div>
