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
        <div class = "collapse navbar-collapse navHeaderCollapse">
            <ul class = "nav navbar-nav navbar-right">
                <li class='hidden-sm hidden-md'><a><img hidden id="ajaxloader" src="/phpietadmin/img/ajax-loader.gif"></a></li>

                <li><a id="menudashboard" class="workspacetab" href='/phpietadmin/dashboard'><span class="glyphicon glyphicon-dashboard"></span> <span class='hidden-sm hidden-md'>Dashboard</span></a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-blackboard"></span> <span class='hidden-sm hidden-md'>Overview</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/overview/disks'>Disks</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/ietvolumes'>Iet volumes</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/ietsessions'>Iet sessions</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/pv'>Physical volumes</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/vg'>Volume groups</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/overview/lv'>Logical volumes</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"></span> <span class='hidden-sm hidden-md'>Targets</span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/targets/addtarget'><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/targets/configuretarget'><span class="glyphicon glyphicon-pencil"></span> Configure</a></li>
                        <li class="divider"></li>
                        <li><a class="workspacetab" href='/phpietadmin/permission/adddisuser'><span class="glyphicon glyphicon-plus"></span> Add discovery user</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/permission/deletedisuser'><span class="glyphicon glyphicon-trash"></span> Delete discovery user</a></li>
                    </ul>
                </li>

                <li><a class="workspacetab" href='/phpietadmin/ietusers'><span class="glyphicon glyphicon-user"></span> <span class='hidden-sm hidden-md'>Users</span></a></li>

                <li><a class="workspacetab" href='/phpietadmin/objects'><span class="glyphicon glyphicon-list"></span> Objects</span></a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" href='/phpietadmin/lvm/add'><span class="glyphicon glyphicon-plus"></span> Add logical volume</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/lvm/delete'><span class="glyphicon glyphicon-trash"></span> Delete logical volume</a></li>
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
                        <li><a class="workspacetab" href='/phpietadmin/config/iet'>IET config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/lvm'>LVM config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/misc'>Misc config</a></li>
                        <li><a class="workspacetab" href='/phpietadmin/config/user'>Login user</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' id="menuconfig" class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-off"></span> <b class = "caret"></b></a>
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

<div id="workspace"></div>

<script>
    require(['common'],function(methods) {
        methods.common();
        methods.add_event_handler_workspacetab();
        methods.add_event_handler_shutdown();
        methods.add_event_handler_reboot();
    });
</script>