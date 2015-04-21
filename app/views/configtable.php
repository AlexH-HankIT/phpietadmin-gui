<ol class="breadcrumb">
    <li class="active">Asphaleia configuration</li>
</ol>
<div id="config-menu">
    <table class="table">
        <tbody>
        <?php while ($row = $result->fetchArray()) { ?>
            <tr>
                <td><?php echo $row[0] ?></td>
                <td><input type="text" name="fname" value="<?php echo $row[1] ?>" disabled> <a href="#<?php echo $row[0]?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> <span class="label label-success bestaetigung">Success</span></td>
                <td><?php if (isset($row[2])) echo $row[2] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>