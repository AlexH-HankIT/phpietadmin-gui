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

                <li class = "active"><a href='/phpietadminv02/home'>Home</a></li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Overview <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadminv02/overview/disks'>Disks</a></li>
                        <li><a href='/phpietadminv02/overview/ietvolumes'>Iet volumes</a></li>
                        <li><a href='/phpietadminv02/overview/ietsessions'>Iet sessions</a></li>
                        <li><a href='/phpietadminv02/overview/pv'>Physical volumes</a></li>
                        <li><a href='/phpietadminv02/overview/vg'>Volume groups</a></li>
                        <li><a href='/phpietadminv02/overview/lv'>Logical volumes</a></li>
                        <li><a href='/phpietadminv02/overview/initiators'>Initiator permissions</a></li>
                        <li><a href='/phpietadminv02/overview/targets'>Target permissions</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Targets <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadminv02/targets/add'>Add</a></li>
                        <li><a href='/phpietadminv02/targets/delete'>Delete</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">Allow <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadminv02/allow/addinitiator'>Add initiator</a></li>
                        <li><a href='/phpietadminv02/allow/deleteinitiator'>Delete initiator</a></li>
                        <li><a href='/phpietadminv02/allow/adduser'>Add user</a></li>
                    </ul>
                </li>

                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown">LVM <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a href='/phpietadminv02/lvm/add'>Add logical volume</a></li>
                        <li><a href='/phpietadminv02/lvm/delete'>Delete logical volume</a></li>
                    </ul>
                </li>

                <li><a href='/phpietadminv02/service'>Service</a></li>

                <li><a href='/phpietadminv02/config'>Config</a></li>
            </ul>
        </div>
    </div>
</div>