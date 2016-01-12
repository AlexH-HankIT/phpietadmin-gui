<div class="workspace">
    <div class="container">
        <table id="table"
            <?php foreach ($data['tableAttributes'] as $head) { ?>
                <?php echo $head ?>
            <?php } ?>>
            <thead>
            <tr>
                <?php foreach ($data['tableHead'] as $body) { ?>
                    <th
                        data-field="<?php echo $body['field'] ?>">
                        <?php echo $body['heading'] ?></th>
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