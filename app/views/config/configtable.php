<div class="container">
    <div id="config-menu">
        <table class="table">
            <tbody>
            <?php foreach ($data as $value) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($value[0]); ?></td>
                        <td>
                            <input size="80" type="text" name="fname" value="<?php echo htmlspecialchars($value[1]); ?>" disabled>
                            <a href="#<?php echo htmlspecialchars($value[0]); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            <span class="label label-success bestaetigung">Success</span>
                        </td>
                    <td><?php if (isset($value[2])) { echo htmlspecialchars($value[2]); } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>