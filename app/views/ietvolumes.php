<div class="workspacedirect">
    <div class="container">
        <?php if (!empty($data[1])) { ?>
            <div class="row top-buffer">
                <ol class="breadcrumb">
                    <li class="active">Iet volumes with luns</li>
                </ol>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <?php foreach ($data[0] as $value) { ?>
                                <th><?php echo htmlspecialchars($value); ?></th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($data[1] as $value) { ?>
                            <?php for ($i = 1; $i < count($value); $i++) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($value[0]['name']); ?></td>
                                    <td><?php echo htmlspecialchars($value[0]['tid']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['path']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['lun']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['state']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['iotype']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['iomode']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['blocks']); ?></td>
                                    <td><?php echo htmlspecialchars($value[$i]['blocksize']); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (!empty($data[2])) { ?>
            <div class="row top-buffer">
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
                            <td><?php echo htmlspecialchars($value['name']); ?></td>
                            <td><?php echo htmlspecialchars($value['tid']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>