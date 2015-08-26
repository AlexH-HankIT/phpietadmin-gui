<div id="workspace">
    <div id="configuretargetmenu" class="container">
        <ul class="nav nav-tabs">
            <li class = "dropdown active">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"> LUN <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li class="active"><a id="configuretargetlunsadd" class="configuretargetnodata" href="/phpietadmin/targets/configure/maplun"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/deletelun"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li class = "dropdown">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-exclamation-sign"></span> ACL <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/addrule"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" href="/phpietadmin/permission/deleterulebuttons"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li class = "dropdown">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-user"></span> Users <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/adduser"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/deleteuser"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/deletesession"><span class="glyphicon glyphicon-list"></span> Sessions</a></li>
            <li><a class="configuretargetiqn" href="/phpietadmin/targets/configure/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
            <li><a class="configuretargetiqn" href="/phpietadmin/targets/deletetarget"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        </ul>
    </div>
    <br>
    <div id="configuretargetbody"></div>

    <script>
        require(['common'],function() {
            require(['pages/configuretarget'],function(methods) {
                methods.hide_menu();
                methods.add_event_handler_targetselection();
                methods.add_event_handler_configuretargetnodata();
                methods.add_event_handler_configuretargetiqn();
            });
        });
    </script>
</div>