<div class="container">
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                    <th>Option</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['options'] as $row) { ?>
                    <tr id="<?php echo $row['option'] ?>">
                        <td><input disabled class="settingstablecheckbox" type="checkbox"/></td>
                        <td class="option"><?php echo $row['option'] ?></td>
                        <td class="valuebeforechange" hidden><?php echo $data['values'][$row['option']] ?></td>
                        <td>
                            <?php ?>

                            <?php ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>