<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete logical volumes</li>
    </ol>
</div>

<div id="logicalvolumedeletecontent" class = "container">
    <div class = "jumbotron">
        <p>
            <select id="logicalvolumedeleteselection" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { ?>
                    <option value="<?=$data[$i]?>"> <?=$data[$i]?> </option>
                <?php } ?>
            </select>
            <br />
            <button class="btn btn-primary" type='submit' id='logicalvolumedeletebutton'>Delete</button>
        </p>
    </div>
    <p>Hint: Only volumes which are not used by the iscsitarget daemon are displayed</p>
</div>