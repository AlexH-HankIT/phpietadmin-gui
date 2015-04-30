<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete logical volumes</li>
    </ol>

    <div class = "jumbotron">
        <p>
        <form method="post">
            LV:
            <select name="volumes" id="volumes" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { ?>
                    <option value="<?=$data[$i]?>"> <?=$data[$i]?> </option>
                <?php } ?>
            </select>
            <br />
            <input class="btn btn-primary" type='submit' value='Delete' onclick="return validateinputnotdefault('volumes', 'Error - Please select a logical volume to delete!')">
        </form>
        </p>
    </div>
    <p>Hint: Only volumes which are not used by the iscsitarget daemon are displayed</p>
</div>