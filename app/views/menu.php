<div class = "navbar navbar-inverse navbar-fixed-top" id="mainmenu">
    <div class = "container">
        <div class="navbar-header">
            <a href = "#" class = "navbar-brand">phpietadmin</a>

            <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>
        </div>
        <div class = "collapse navbar-collapse navHeaderCollapse">
            <ul class = "nav navbar-nav navbar-right">

                <li class="active"><a id="menudashboard" class="workspacetab" name="dashboard" href='#dashboard'><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-blackboard"></span> Overview <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" name="overview/disks" href='#overview'>Disks</a></li>
                        <li><a class="workspacetab" name="overview/ietvolumes" href='#overview'>Iet volumes</a></li>
                        <li><a class="workspacetab" name="overview/ietsessions" href='#overview'>Iet sessions</a></li>
                        <li><a class="workspacetab" name="overview/pv" href='#overview'>Physical volumes</a></li>
                        <li><a class="workspacetab" name="overview/vg" href='#overview'>Volume groups</a></li>
                        <li><a class="workspacetab" name="overview/lv" href='#overview'>Logical volumes</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"></span> Targets <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" name="targets/addtarget" href='#targets'><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="workspacetab" name="targets/configuretarget" href='#targets'><span class="glyphicon glyphicon-pencil"></span> Configure</a></li>
                    </ul>
                </li>

                <li><a class="workspacetab" name="objects" href='#objects'><span class="glyphicon glyphicon-list"></span> Objects</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-user"></span> Users <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" name="ietusers" href='#ietusers'>Users</a></li>
                        <li><a class="workspacetab" name="permission/adddisuser" href='#permission'>Add discovery user</a></li>
                        <li><a class="workspacetab" name="permission/deletedisuser" href='#permission'>Delete discovery user</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" name="lvm/add" href='#lvm'>Add logical volume</a></li>
                        <li><a class="workspacetab" name="lvm/delete" href='#lvm'>Delete logical volume</a></li>
                    </ul>
                </li>

                <li><a class="workspacetab" name="service" href='#service'>Service</a></li>

                <li class = "dropdown">
                    <a href='#' id="menuconfig" class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-cog"></span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="workspacetab" name="config/iet" href='#config'>IET config</a></li>
                        <li><a class="workspacetab" name="config/lvm" href='#config'>LVM config</a></li>
                        <li><a class="workspacetab" name="config/misc" href='#config'>Misc config</a></li>
                        <li><a class="workspacetab" name="config/user" href='#config'>Login user</a></li>
                    </ul>
                </li>

                <li><a id="menulogout" href='/phpietadmin/auth/logout'><span class="glyphicon glyphicon-off"></span></a></li>
            </ul>
        </div>
    </div>
</div>

<div id="workspace"></div>

<script>
    require(['common'],function() {
        require(['pages/loadworkspace']);
    });
</script>

