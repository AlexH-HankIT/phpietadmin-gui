<div class = "container">
    <div class = "jumbotron">
        <form method="post">
            <p>Name: <input class="form-control" type="text" name="name" id="name"/></p>
            <p>
                LV:
                <select name="path" id="path" class="form-control">
                    <option id="default">Select...</option>
                    <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
                </select>
            </p>
            <p><input class="btn btn-primary" type="submit" value="Create" onclick="return validatetargetadd();"/></p>
        </form>
    </div>
</div>