<div class="workspacedirect">
    <div class = "container">
        <div id="mapluncontent">
            <div class = "jumbotron">
                <div class="row">
                    <div class="col-md-10">
                        <div id="maplunautoselection">
                            <select name="path" id="logicalvolume" class="form-control">
                                <option id="logicalvolumedefault">Select logical volume</option>
                                <?php foreach ($data['logicalvolumes'] as $value) { ?>
                                    <option value="<?php echo htmlspecialchars($value) ?>"> <?php echo htmlspecialchars($value) ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-1">
                        <button id="maplunbutton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
                    </div>
                    <!--<p><span style="font-size:smaller;"><input id="check" type="checkbox" value="uc" name="Show"> Manual input</span></p>-->
                </div>
                <div class="row top-buffer">
                    <div class="col-md-5">
                        <select name="type" id="type" multiple class="form-control">
                            <option value="fileio">fileio</option>
                            <option selected value="blockio">blockio</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="mode" id="mode" multiple class="form-control">
                            <option selected value="wt" >Write through</option>
                            <option value="ro" >Read only</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/maplun'],function(methods) {
                methods.add_event_handler_maplunbutton();
            });
        });
    </script>
</div>