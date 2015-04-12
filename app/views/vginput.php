<!-- Do this with ajax later! -->
<div id="leftDiv">
    <form method="post" onSubmit="return checkstatevalue()">
        <select name="vg_post" id="vg" onchange="check('vg', 'default');">
            <option value="default" id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
        <input type='submit' value='Show'>
    </form>
</div>