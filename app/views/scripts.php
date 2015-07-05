<script class="<?php echo $data['class'] ?>">
    require(['common'],function() {
        require(["pages/<?php echo $data['page'] ?>"],function(methods) {
            <?php foreach ($data['methods'] as $methods) { ?>
                methods.<?php echo $methods ?>;
            <?php } ?>
        });
    });
</script>