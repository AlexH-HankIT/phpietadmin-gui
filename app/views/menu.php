<div class = "navbar navbar-inverse navbar-fixed-top" id="mainmenu">
    <div class = "container">
        <div class="navbar-header">
            <a href = "#" class = "navbar-brand hidden-sm hidden-md">phpietadmin</a>

            <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>
        </div>
        <div id="main_menu_bar" class = "collapse navbar-collapse navHeaderCollapse">
            <ul class = "nav navbar-nav navbar-right">
                <li><a id="menudashboard" class="workspacetab" href='/phpietadmin/dashboard'><span class="glyphicon glyphicon-dashboard"></span> <span class='hidden-sm hidden-md'> Dashboard</span></a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-blackboard"></span> <span class='hidden-sm hidden-md'> Overview</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/overview/disks'>Disks</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/iet/volume'>Iet volumes</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/iet/session'>Iet sessions</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/lvm/pv'>Physical volumes</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/lvm/vg'>Volume groups</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/lvm/lv'>Logical volumes</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"></span> <span class='hidden-sm hidden-md'> iSCSI</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/targets/addtarget'><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/targets/configure'><span class="glyphicon glyphicon-wrench"></span> Configure</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/targets/adddisuser'><span class="glyphicon glyphicon-plus"></span> Add discovery user</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/targets/deletedisuser'><span class="glyphicon glyphicon-trash"></span> Delete discovery user</a></li>
                    </ul>
                </li>

                <li><a class="workspacetab" href='/phpietadmin/ietusers'><span class="glyphicon glyphicon-user"></span> <span class='hidden-sm hidden-md'>iSCSI users</span></a></li>
                <li><a class="workspacetab" href='/phpietadmin/objects'>Objects</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/lvm/add'><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/lvm/configure'><span class="glyphicon glyphicon-wrench"></span> Configure</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Services <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/service'><span class="glyphicon glyphicon-blackboard"></span> Overview</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/service/add'><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' id="menuconfig" class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-cog"></span> <span class='hidden-sm hidden-md'>Config</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/config/show/iet'>IET config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/show/misc'>Misc config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/show/bin'>Bin config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/show/logging'>Logging config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/show/backup'>Backup config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/user'>User</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-list"></span> <span class='hidden-sm hidden-md'>Logs</span> <b class = "caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/log/show/access'>Access</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/log/show/action'>Action</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-off"></span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/auth/logout'><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        <li><a id="menushutdownbutton" href='/phpietadmin/service/hold'><span class="glyphicon glyphicon-off"></span> Shutdown</a></li>
                        <li><a id="menurebootbutton" href='/phpietadmin/service/hold'><span class="glyphicon glyphicon-repeat"></span> Reboot</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<div id='workspace_wrapper'></div>

<script>
    require(['common'],function(methods) {
        methods.common();
        methods.load_workspace_event_handler();
        methods.add_event_handler_shutdown();
        methods.add_event_handler_reboot();
    });
</script>