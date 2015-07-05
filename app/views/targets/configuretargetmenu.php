<div class="workspacedirect">
    <div id="configuretargetmenu" class="container">
        <ul class="nav nav-tabs">
            <li class = "dropdown active">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"> LUN <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li class="active"><a id="configuretargetlunsadd" class="configuretargetnodata" name="targets/maplun" href="#"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" name="targets/deletelun" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li class = "dropdown">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-exclamation-sign"></span> ACL <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li><a class="configuretargetiqn" name="permission/addrule" href="#"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" name="permission/deleterule" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li class = "dropdown">
                <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-user"></span> Users <b class = "caret"></b></a>
                <ul class = "dropdown-menu">
                    <li><a class="configuretargetiqn" name="permission/adduser" href="#"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                    <li><a class="configuretargetiqn" name="permission/deleteuser" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </li>
            <li><a class="configuretargetiqn" name="targets/deletesession" href="#"><span class="glyphicon glyphicon-list"></span> Sessions</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
            <li><a class="configuretargetiqn" name="targets/deletetarget" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
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