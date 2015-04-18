<div id="leftDiv">
    <form method="post">
        <select name="IQNs2" id="iqn">
            <option id="default">Select...</option>
            <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
        </select>
        <input type="submit" value="Delete" onclick="return validateinitiatorallowdelete();">
    </form>
</div>