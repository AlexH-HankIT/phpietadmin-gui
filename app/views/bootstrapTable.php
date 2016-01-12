<div class="workspace">
    <div class="container">
        <table id="table"
            <?php foreach ($data['tableAttributes'] as $head) { ?>
                <?php echo $head ?>
            <?php } ?>>
            <thead>
            <tr>
                <?php foreach ($data['tableHead'] as $tableHead) { ?>
                    <th <?php foreach ($tableHead['fields'] as $fields) echo $fields ?>><?php echo $tableHead['heading'] ?></th>
                <?php } ?>
            </tr>
            </thead>
        </table>
    </div>

    <script>
        require(['common'],function() {
            require(['domReady', "<?php echo $data['js'] ?>"] ,function(domReady, methods) {
                domReady(function () {
                    methods.table();
                });
            });
        });
    </script>
</div>