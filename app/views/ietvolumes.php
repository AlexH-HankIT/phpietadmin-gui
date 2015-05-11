<div class="container">
    <?php if (!empty($data[1])) { ?>
        <ol class="breadcrumb">
            <li class="active">Iet volumes with luns</li>
        </ol>

        <table class="table table-striped">
            <thead>
                <tr>
                    <?php foreach ($data[0] as $value) { ?>
                        <th><?php echo $value ?></th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data[1] as $value) { ?>
                    <?php for ($i = 1; $i < count($value); $i++) { ?>
                        <tr>
                            <td><?php echo $value[0]['name'] ?></td>
                            <td><?php echo $value[0]['tid'] ?></td>
                            <td><?php echo $value[$i]['path'] ?></td>
                            <td><?php echo $value[$i]['lun'] ?></td>
                            <td><?php echo $value[$i]['state'] ?></td>
                            <td><?php echo $value[$i]['iotype'] ?></td>
                            <td><?php echo $value[$i]['iomode'] ?></td>
                            <td><?php echo $value[$i]['blocks'] ?></td>
                            <td><?php echo $value[$i]['blocksize'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
    <?php } ?>

    <?php if (isset($data[2])) { ?>
            <ol class="breadcrumb">
                <li class="active">Iet volumes without luns</li>
            </ol>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>name</th>
                        <th>tid</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data[2] as $value) { ?>
                        <tr>
                            <td><?php echo $value['name'] ?></td>
                            <td><?php echo $value['tid'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
</div>