<div class="workspace">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li class='active'><a href='#'>Configure</a></li>
                <li><a id="dynamicBreadcrumb" href='#'>maplun</a></li>
            </ol>
            <div class='panel-body'>
                <select id="targetSelect" data-live-search="true" data-width="100%" title="Select a target to configure" data-size="10">
                    <?php foreach ($data['targets'] as $target) { ?>
                        <option <?php if (isset($target['lun'])) {
                            echo "data-subtext=\"" . count($target['lun']) . " lun/s\"";
                        } ?>
                            <?php if ($target['iqn'] === $data['iqn']) echo 'selected' ?>
                            value="<?php echo htmlspecialchars($target['iqn']); ?>"><?php echo htmlspecialchars($target['iqn']); ?>
                        </option>
                    <?php } ?>
                </select>
                <div id="configureTargetMenu"></div>
                <div class="top-buffer" id="configureTargetBodyLoadingIndicator" hidden>
                    <div class="container text-center">
                        Loading, please wait...
                    </div>
                </div>
                <div class="top-buffer" id='configureTargetBody'></div>
            </div>
        </div>
    </div>
    <script>
        require(['common'], function () {
            require(['pages/target/configureTarget', 'domReady'], function (methods, domReady) {
                domReady(function () {
                    methods.addEventHandler();
                    methods.initialLoad();
                });
            });
        });
    </script>
</div>