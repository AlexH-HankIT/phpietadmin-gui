<div class="container">
    <ol class="breadcrumb">
        <li class="active"><?php echo htmlspecialchars($data['title']); ?></li>
    </ol>

    <form method="post">
        <table class = "table table-striped">
            <tr>
                <?php foreach ($data[0] as $value) { ?>
                    <th><?php echo htmlspecialchars($value); ?></th>
                <?php } ?>
                <!--<th>Disconnect</th>-->
            </tr>

            <?php foreach ($data[1] as $value) { ?>
                    <?php for ($i=1; $i < count($value); $i++) { ?>
                        <tr>
                            <!-- Hidden input fields to save the data which is posted to the server -->
                            <input type="hidden" name="tid" value="<?php echo htmlspecialchars($value[0]['tid']); ?>">
                            <input type="hidden" name="cid" value="<?php echo htmlspecialchars($value[$i]['cid']); ?>">
                            <input type="hidden" name="sid" value="<?php echo htmlspecialchars($value[$i]['sid']); ?>">

                            <td><?php echo htmlspecialchars($value[0]['name']); ?></td>
                            <td><?php echo htmlspecialchars($value[0]['tid']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['sid']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['initiator']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['cid']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['ip']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['state']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['hd']); ?></td>
                            <td><?php echo htmlspecialchars($value[$i]['dd']); ?></td>
                            <!--<td><p data-placement="top" data-toggle="tooltip" title="Disconnect"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>-->
                        </tr>
                    <?php } ?>
            <?php } ?>
        </table>
    </form>
</div>