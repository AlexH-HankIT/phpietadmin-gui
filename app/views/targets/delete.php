<div id="leftDiv">
    <form method="post">
        <select name="IQN">
            <option value="">Select...</option>
            <?php for ($i = 0; $i < count($data[1]); $i++) { echo '<option value=' . ($i+1) . '>' . $data[1][$i] . '</option>'; } ?>
        </select>
        <input type="submit" value="Delete">
    </form>
</div>