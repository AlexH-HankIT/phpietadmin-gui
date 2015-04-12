<div id="leftDiv">
    <form method="post">
        <select name="IQNs">
            <option value="">Select...</option>
            <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
        </select>
        <p>IP: <input type="text" name="ip" /></p>
        <p>Source IP of the client which should connect to this target</p>
        <p>You can seperate multiple ips with a comma</p>
        <input type="submit" value="Allow">
    </form>
</div>