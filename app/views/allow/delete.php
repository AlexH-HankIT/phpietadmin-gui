<div id="leftDiv">
    <form method="post">
        <select name="IQNs2">
            <option value="">Select...</option>
            <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
        </select>
        <input type="submit" value="Delete">
    </form>
</div>