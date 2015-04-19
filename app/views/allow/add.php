<div class = "container">
    <h1>Add initiator permission</h1>
    <div class = "jumbotron">
        <form method="post">
            <select name="IQNs" id="iqn" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
            <br />
            <input class="form-control" type="text" name="ip" id="ip"/>
            <p>Source IP of the client which should connect to this target</p>
            <p>You can seperate multiple ips with a comma</p>
            <input class="btn btn-primary" type="submit" value="Allow" onclick="return validateinitiatorallowadd()">
        </form>
    </div>
</div>