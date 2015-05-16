<div id="addtargetinput" class="container">
    <ol class="breadcrumb">
        <li class="active">Add target</li>
    </ol>

    <!-- Hidden field to save default iqn -->
    <input value="<?php echo $data ?>" id="defaultiqn" type="hidden"/>

    <div class = "jumbotron">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-1">
                    <label for="iqninput" class="control-label">IQN:</label>
                    <br />
                    <br />
                    <button class="btn btn-primary" type='submit' id="addtargetbutton">Add</button>
                </div>
                <div class="col-sm-11">
                    <input class="form-control focusedInput" type="text" value="<?php echo $data ?>" id="iqninput" required/>
                </div>
            </div>
        </div>
    </div>
</div>