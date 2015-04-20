<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete initiator permission</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <select name="IQNs2" id="iqn" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
            <br />
            <input class="btn btn-primary" type="submit" value="Delete" onclick="return validateinitiatorallowdelete();">
        </form>
    </div>
</div>