<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add user</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <select name="iqn" id="iqn" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data[1]); $i++) { echo '<option value=' . $data[1][$i] . '>' . $data[1][$i] . '</option>'; } ?>
            </select>
            <br />

            User:
            <input class="form-control" type="text" name="user" id="user"/>

            <br />

            Password:
            <input class="form-control" type="text" name="pass" id="pass"/>

            <br />
            <input class="btn btn-primary" type="submit" value="Allow" onclick="return validateinitiatorallowadd()">
        </form>
    </div>
</div>