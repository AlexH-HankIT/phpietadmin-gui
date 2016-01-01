<div class="table-responsive">
    <table class='table'>
        <thead>
        <tr>
            <th><input id="masterCheckbox" type="checkbox"></th>
            <th>Type</th>
            <th>Name</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><input class="object_delete_checkbox" type="checkbox"></td>
                <td><?php if (isset($row['display_name'])) echo htmlspecialchars($row['display_name']); else echo 'N/A' ?></td>
                <td><?php if (isset($row['name'])) echo htmlspecialchars($row['name']); else echo 'N/A' ?></td>
                <td class='objectValue'><?php echo htmlspecialchars($row['value']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>