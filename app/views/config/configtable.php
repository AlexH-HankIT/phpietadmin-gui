<div class="container">
    <!--   <ol class="breadcrumb">
           <li class="active">configuration</li>
       </ol>-->
    <div id="config-menu">
        <table class="table">
            <tbody>
            <?php foreach ($data as $value) { ?>
                <tr>
                    <td><?php echo $value[0] ?></td>
                        <td>
                            <input size="80" type="text" name="fname" value="<?php echo $value[1] ?>" disabled>
                            <a href="#<?php echo $value[0]?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            <span class="label label-success bestaetigung">Success</span>
                        </td>
                    <td><?php if (isset($value[2])) echo $value[2] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>