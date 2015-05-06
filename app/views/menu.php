<div class = "navbar navbar-inverse navbar-static-top">
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

                <li><a href='/phpietadmin/home'>Home</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Overview <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/overview/disks'>Disks</a></li>
                        <li><a href='/phpietadmin/overview/ietvolumes'>Iet volumes</a></li>
                        <li><a href='/phpietadmin/overview/ietsessions'>Iet sessions</a></li>
                        <li><a href='/phpietadmin/overview/pv'>Physical volumes</a></li>
                        <li><a href='/phpietadmin/overview/vg'>Volume groups</a></li>
                        <li><a href='/phpietadmin/overview/lv'>Logical volumes</a></li>
                        <li><a href='/phpietadmin/overview/initiators'>Initiator permissions</a></li>
                        <li><a href='/phpietadmin/overview/targets'>Target permissions</a></li>
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
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Permissions <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadmin/permission/addinitiator'>Add initiator</a></li>
                        <li><a href='/phpietadmin/permission/deleteinitiator'>Delete initiator</a></li>
                        <li><a href='/phpietadmin/permission/adduser'>Add user (BUGGY)</a></li>
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

                <!--<li><a href='/phpietadmin/config'>Config</a></li>-->

                <li><a href='/phpietadmin/auth/logout'>Logout</a></li>
            </ul>
        </div>
    </div>
</div>