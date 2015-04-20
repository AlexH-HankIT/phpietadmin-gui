<div class = "container">
    <ol class="breadcrumb">
        <li class="active"><?=$data[2]?></li>
    </ol>

    <table class = "table table-striped">
        <tr>
            <?php foreach ($data[0] as $value): ?>
                <th><?php echo $value ?></th>
                <?php endforeach; ?>
                <?php foreach ($data[1] as $value): ?>
        <tr>
            <?php for ($i=0; $i<count($value); $i++) { ?>
                <td><?php echo $value[$i] ?></td>
            <?php } ?>
        </tr>
            <?php endforeach; ?>
    </table>
</div>