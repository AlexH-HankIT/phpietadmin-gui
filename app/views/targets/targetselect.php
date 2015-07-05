<div class="workspacedirect">
    <div class="container">
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-12">
                    <select id="targetselection" class="form-control">
                        <option id="default">Select a target to configure</option>
                        <?php foreach ($data['targets'] as $value) { ?>
                            <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>