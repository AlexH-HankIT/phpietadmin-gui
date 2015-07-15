<script class="<?php echo htmlspecialchars($data['class']) ?>">
    require(['common'],function() {
        require(["pages/<?php echo htmlspecialchars($data['page']) ?>"],function(methods) {
            <?php foreach ($data['methods'] as $methods) { ?>
                methods.<?php echo htmlspecialchars($methods) ?>;
            <?php } ?>
        });
    });
</script>