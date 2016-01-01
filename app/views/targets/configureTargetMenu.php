<ul class="nav nav-tabs top-buffer">
    <li class="dropdown">
        <a href='#' class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-hdd"> LUN <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#maplun"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#deletelun"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href='#' class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-exclamation-sign"></span> ACL <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#addrule"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#deleterule"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href='#' class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Users
            <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#adduser"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
            <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#deleteuser"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        </ul>
    </li>
    <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#session"><span class="glyphicon glyphicon-list"></span> Sessions</a></li>
    <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
    <li class="configureTargetBodyMenu"><a href="<?php echo WEB_PATH ?>/targets/configure/<?php echo $data ?>#delete"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs"> Delete</span></a></li>
</ul>