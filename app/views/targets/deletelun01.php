<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete lun</li>
    </ol>
    <div id="deleteluncontent">
        <div class="jumbotron">
            <p>
                <select id="deleteluniqnselection" class="form-control">
                    <option value="default" id="default">Select target</option>
                    <?php foreach ($data as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
                    <?php } ?>
                </select>
            </p>

            <!-- list with luns will be inserted here using ajax -->
            <div id="deletelunluns"></div>
        </div>
    </div>
</div>