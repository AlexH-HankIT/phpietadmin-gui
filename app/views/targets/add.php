<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add target</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <p>Name: <input class="form-control" type="text" name="name" id="name" required/></p>
            <p>
                LV:
                <select name="path" id="path" class="form-control">
                    <option id="default">Select...</option>
                    <?php for ($i = 0; $i < count($data); $i++) { ?>
                        <option value="<?=$data[$i]?>"> <?=$data[$i]?> </option>
                    <?php } ?>
                </select>
            </p>
            <div class = "row">
                <div class = "col-md-6">
                    <p>
                        Type:
                        <select name="type" id="type" multiple class="form-control">
                            <option value="fileio">fileio</option>
                            <option selected value="blockio" >blockio</option>
                        </select>
                    </p>
                </div>
                <div class = "col-md-6">
                    <p>
                        Mode:
                        <select name="mode" id="mode" multiple class="form-control">
                            <option selected value="wt" >Write through</option>
                            <option value="ro" >Read only</option>
                        </select>
                    </p>
                </div>
            </div>
            <p><input class="btn btn-primary" type="submit" value="Create" onclick="return validateinputnotdefault('path', 'Error - Please select a volume!')"/></p>
        </form>
    </div>
</div>