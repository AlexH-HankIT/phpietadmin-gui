<div class="workspace">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li class="active">Dashboard</li>
            </ol>
            <table class = "table table-striped">
                <thead>
                <tr>
                    <th colspan="2" class="dashboardheader">System information</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="dashboardoption">Hostname</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['hostname']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">version</td>
                    <td id="phpietadminVersion" class="dashboardvalue" data-version="<?php echo htmlspecialchars($data['phpietadminversion']); ?>">
                        <?php echo htmlspecialchars($data['phpietadminversion']); ?>
                        <button data-url="<?php echo WEB_PATH ?>/config/release" id='versionCheck' class="btn btn-info btn-xs text-bold">Getting version...</button>
                    </td>
                </tr>
                <tr>
                    <td class="dashboardoption">release</td>
                    <td class="dashboardvalue">
                        <?php if ($data['release'] === 'stable') { ?>
                            <button class="btn btn-success btn-xs text-bold">stable</button>
                        <?php } else { ?>
                            <button class="btn btn-danger btn-xs text-bold"><?php echo htmlspecialchars($data['release']); ?></button>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="dashboardoption">Distribution</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['distribution']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">CPU</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['cpu']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">System time</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['systemtime']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">System start</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['systemstart']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">Uptime</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['uptime']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">Current load</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['currentload']); ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">Memory usage</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['memused']) . ' mb of ' . htmlspecialchars($data['memtotal']) . ' mb used'; ?></td>
                </tr>
                <tr>
                    <td class="dashboardoption">Kernel</td>
                    <td class="dashboardvalue"><?php echo htmlspecialchars($data['kernel']); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <script>
            require(['common'],function() {
                require(['pages/dashboard', 'domReady'],function(methods, domReady) {
                    domReady(function () {
                        methods.checkVersion();
                    });
                });
            });
        </script>
    </div>
</div>