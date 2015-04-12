<div id="leftDiv">
    <p>
    <form method="post">
        LV:
        <select name="volumes">
            <option value="">Select...</option>
            <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
        </select>
        <input type='submit' value='Delete'>
    </form>
    </p>
    <p>Hint: Only volumes which are not used by the iscsitarget daemon are displayed</p>
</div>