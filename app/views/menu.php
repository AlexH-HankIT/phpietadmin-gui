<div class = "navbar navbar-inverse navbar-static-top">
    <div class = "container">
        <div class="navbar-header">
            <a href = "#" class = "navbar-brand hidden-sm hidden-md"><?php echo PROJECT_NAME ?></a>
            <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>
        </div>
        <div class = "collapse navbar-collapse navHeaderCollapse">
            <ul class = "nav navbar-nav navbar-right">
                <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/dashboard"><span class="glyphicon glyphicon-dashboard"></span> <span class='hidden-sm hidden-md'> Dashboard</span></a></li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-blackboard"></span> <span class='hidden-sm'> Overview</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/disks">Disks</a></li>
                        <li class="divider"></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/iet/volume">Iet volumes</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/iet/session">Iet sessions</a></li>
                        <li class="divider"></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/lvm/pv">Physical volumes</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/lvm/vg">Volume groups</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/overview/lvm/lv">Logical volumes</a></li>
                    </ul>
                </li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"></span> <span class='hidden-sm'> iSCSI</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/targets/addtarget"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/targets/configure"><span class="glyphicon glyphicon-wrench"></span> Configure</a></li>
                        <li class="divider"></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/targets/adddisuser"><span class="glyphicon glyphicon-plus"></span> Add discovery user</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/targets/deletedisuser"><span class="glyphicon glyphicon-trash"></span> Delete discovery user</a></li>
                    </ul>
                </li>
                <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/ietusers"><span class="glyphicon glyphicon-user"></span> <span>iSCSI users</span></a></li>
                <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/objects">Objects</a></li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/lvm/add"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/lvm/configure"><span class="glyphicon glyphicon-wrench"></span> Configure</a></li>
                    </ul>
                </li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Services <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/service"><span class="glyphicon glyphicon-blackboard"></span> Overview</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/service/add"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    </ul>
                </li>
                <li class = "dropdown">
                    <a href='#' id="menuconfig" class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-cog"></span> <span class='hidden-sm'>Config</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/show/iet">IET config</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/show/misc">Misc config</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/show/bin">Bin config</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/show/logging">Logging config</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/show/backup">Backup config</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/user">User</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/config/release">Releases</a></li>
                    </ul>
                </li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-list"></span> <span class='hidden-sm'>Logs</span> <b class = "caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/log/show/access">Access</a></li>
                        <li><a class="workspaceTab" href="<?php echo WEB_PATH ?>/log/show/action">Action</a></li>
                    </ul>
                </li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-off"></span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href="<?php echo WEB_PATH ?>/auth/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        <li><a id="shutdown" href="<?php echo WEB_PATH ?>/service/hold"><span class="glyphicon glyphicon-off"></span> Shutdown</a></li>
                        <li><a id="reboot" href="<?php echo WEB_PATH ?>/service/hold"><span class="glyphicon glyphicon-repeat"></span> Reboot</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="waitIndicator" hidden>
    <div class="container text-center">
        Loading, please wait...
    </div>
</div>
<div id='workspace_wrapper'></div>
<script>
    require(['common'],function(methods) {
        methods.common();
        methods.menu();
    });
</script>