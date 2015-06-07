<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add user</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <select name="iqn" id="iqn" class="form-control">
                <option id="default">Select...</option>
                <?php foreach ($data[1] as $row) { ?>
                    <option value="<?php echo htmlspecialchars($row); ?>"><?php echo htmlspecialchars($row); ?></option>
                <?php } ?>
            </select>
            <br />

            User:
            <input class="form-control" type="text" name="user" id="user" required/>

            <br />

            Password:
            <input class="form-control" type="text" name="pass" id="pass" required/>

            <br />
            <input class="btn btn-primary" type="submit" value="Allow" onclick="return validateinputnotdefault('iqn', 'Error - Please select a target')">
        </form>
    </div>
</div>