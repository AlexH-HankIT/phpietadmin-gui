<div class = "navbar navbar-inverse navbar-fixed-top">
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

                <li><a id="menuhome" href='/phpietadmin/home'><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href='/phpietadmin/dashboard'> Dashboard</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Overview <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/overview/disks'>Disks</a></li>
                        <li><a href='/phpietadmin/overview/ietvolumes'>Iet volumes</a></li>
                        <li><a href='/phpietadmin/overview/ietsessions'>Iet sessions</a></li>
                        <li><a href='/phpietadmin/overview/pv'>Physical volumes</a></li>
                        <li><a href='/phpietadmin/overview/vg'>Volume groups</a></li>
                        <li><a href='/phpietadmin/overview/lv'>Logical volumes</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Targets <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/targets/addtarget'>Add target</a></li>
                        <li><a href='/phpietadmin/targets/maplun'>Map lun</a></li>
                        <li><a href='/phpietadmin/targets/deletetarget'>Delete target</a></li>
                        <li><a href='/phpietadmin/targets/deletelun'>Delete lun</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Rules <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/objects'>Objects</a></li>
                        <li><a href='/phpietadmin/permission/addrule'>Add rule</a></li>
                        <li><a href='/phpietadmin/permission/deleterule'>Delete rule</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Users <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/ietusers'>Users</a></li>
                        <li><a href='/phpietadmin/permission/adduser'>Add user to target</a></li>
                        <li><a href='/phpietadmin/permission/adduser'>Delete user from target</a></li>
                        <li><a href='/phpietadmin/permission/adduser'>Add global user</a></li>
                        <li><a href='/phpietadmin/permission/adduser'>Delete global user</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/lvm/add'>Add logical volume</a></li>
                        <li><a href='/phpietadmin/lvm/delete'>Delete logical volume</a></li>
                    </ul>
                </li>

                <li><a href='/phpietadmin/service'>Service</a></li>

                <li class = "dropdown">
                    <a href='#' id="menuconfig" class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-cog"></span> <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/config/iet'>IET config</a></li>
                        <li><a href='/phpietadmin/config/lvm'>LVM config</a></li>
                        <li><a href='/phpietadmin/config/misc'>Misc config</a></li>
                    </ul>
                </li>

                <li><a id="menulogout" href='/phpietadmin/auth/logout'><span class="glyphicon glyphicon-off"></span></a></li>
            </ul>
        </div>
    </div>
</div>