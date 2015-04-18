<div id="leftDiv">
    <form method="post">
        <select name="IQN" id="targetdelete">
            <option id="default">Select...</option>
            <?php for ($i = 0; $i < count($data[1]); $i++) { echo '<option value=' . ($i+1) . '>' . $data[1][$i] . '</option>'; } ?>
        </select>
        <input type="submit" value="Delete" onclick="return validatetargetdelete()">
    </form>
</div>