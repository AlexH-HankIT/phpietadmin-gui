<script type="application/javascript">
    $(document).ready(function() {
        $('#manuelinput').hide();

        $("#check").change(function() {
            if($('#manuelinput').is(':hidden')) {
                $("#manuelinput").attr("required");
                $("#manuelinput").show();
                $("#autoselection").hide();
            } else {
                $("#autoselection").show();
                $("#manuelinput").hide();
            }
        });
    });
</script>

<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Map lun</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <p>
                Targets:
                <select name="target" id="target" class="form-control">
                    <option id="default">Select...</option>
                    <?php foreach ($data['targets'] as $value) { ?>
                        <option value="<?=$value?>"> <?=$value?> </option>
                    <?php } ?>
                </select>
            </p>
            <br />

            <div id="manuelinput">
                <p>
                    Path to block device:<input class="form-control" type="text" value="/dev/" name="pathtoblockdevice" id="pathtoblockdevice"/>
                </p>
            </div>

            <div id="autoselection">
                <p>
                    Logical volumes:
                    <select name="path" id="logicalvolume" class="form-control">
                        <option id="default">Select...</option>
                        <?php foreach ($data['logicalvolumes'] as $value) { ?>
                            <option value="<?=$value?>"> <?=$value?> </option>
                        <?php } ?>
                    </select>
                </p>
            </div>
            <p><span style="font-size:smaller;"><input id="check" type="checkbox" value="uc" name="Show"> Manual input</span></p>

            <br />

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
            <p><input class="btn btn-primary" type="submit" value="Map" onclick="return validatemaplun();"/></p>
        </form>
    </div>
</div>