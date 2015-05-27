<div id="addtargetinput" class="container">
    <ol class="breadcrumb">
        <li class="active">Add target</li>
    </ol>

    <!-- Hidden field to save default iqn -->
    <input value="<?php echo $data ?>" id="defaultiqn" type="hidden"/>

    <div class = "jumbotron">
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><?php echo $data ?></span>
            <input class="form-control focusedInput" type="text" id="iqninput" required/>
        </div>
        <br />
        <button class="btn btn-primary" type='submit' id="addtargetbutton">Add</button>
    </div>
</div>