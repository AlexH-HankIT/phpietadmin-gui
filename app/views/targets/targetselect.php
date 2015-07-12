<div class="workspacedirect">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li class='active'>Configure</a></li>
            </ol>

            <div class='panel-body'>
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