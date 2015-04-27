<div class="container">
    <ol class="breadcrumb">
        <li class=active"><?php echo $data['title']?></li>
    </ol>

    <table class = "table table-striped">
        <tr>
            <?php foreach ($data[0] as $value) { ?>
                <th><?php echo $value ?></th>
            <?php } ?>
        </tr>

        <?php foreach ($data[1] as $value) { ?>
                <?php for ($i=1; $i < count($value); $i++) { ?>
                    <tr>
                        <td><?php echo $value[0]['name']?></td>
                        <td><?php echo $value[0]['tid']?></td>
                        <td><?php echo $value[$i]['sid']?></td>
                        <td><?php echo $value[$i]['initiator']?></td>
                        <td><?php echo $value[$i]['cid']?></td>
                        <td><?php echo $value[$i]['ip']?></td>
                        <td><?php echo $value[$i]['state']?></td>
                        <td><?php echo $value[$i]['hd']?></td>
                        <td><?php echo $value[$i]['dd']?></td>
                    </tr>
                <?php } ?>
        <?php } ?>
    </table>
</div>