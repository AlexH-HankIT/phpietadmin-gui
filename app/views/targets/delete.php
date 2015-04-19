<div class = "container">
    <div class = "jumbotron">
        <form method="post">
            <select name="IQN" id="targetdelete" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data[1]); $i++) { echo '<option value=' . ($i+1) . '>' . $data[1][$i] . '</option>'; } ?>
            </select>
            <br />
            <input class="btn btn-primary" type="submit" value="Delete" onclick="return validatetargetdelete()">
        </form>
    </div>
</div>