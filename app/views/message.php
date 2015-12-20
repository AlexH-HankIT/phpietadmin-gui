<div class="workspace">
    <div class = "container">
        <?php if (is_array($data)) { ?>
            <?php if (!empty($data['type'])) { ?>
                <?php if ($data['type'] == 'success') { ?>
                    <div class="alert alert-success" role="alert"><h3 class="center"><?php echo htmlspecialchars($data['message']); ?></h3></div>
                <?php } else if ($data['type'] == 'warning') { ?>
                    <div class="alert alert-warning" role="alert"><h3 class="center"><?php echo htmlspecialchars($data['message']); ?></h3></div>
                <?php } else if ($data['type'] == 'danger') { ?>
                    <div class="alert alert-danger" role="alert"><h3 class="center"><?php echo htmlspecialchars($data['message']); ?></h3></div>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert"><h3 class="center"><?php echo htmlspecialchars($data['message']); ?></h3></div>
                <?php } ?>
            <?php } else { ?>
                <div class="alert alert-info" role="alert"><h3 class="center"><?php echo htmlspecialchars($data['message']); ?></h3></div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-info" role="alert"><h3 class="center"><?php echo htmlspecialchars($data); ?></h3></div>
        <?php } ?>
    </div>
</div>