<div class="workspace">
    <div class="container">
        <table
            id="table"
            data-show-export="true"
            <?php foreach ($data['head'] as $head) { ?>
                <?php echo $head ?>
            <?php } ?>>
            <thead>
            <tr>
                <?php foreach ($data['body'] as $body) { ?>
                <th data-field="<?php echo $body['field'] ?>"><?php echo $body['heading'] ?></th>
                <?php } ?>
            </tr>
            </thead>
        </table>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/bootstrapTable', 'domReady'],function(methods, domReady) {
                domReady(function () {
                    methods.table();
                });
            });
        });
    </script>
</div>