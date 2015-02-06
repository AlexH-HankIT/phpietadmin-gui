<?php
   // Get service status ;-)
   $return = get_service_status($service);

    // Get system load
    $load = sys_getloadavg();
    $out = "<p>Current load: " . $load[0] . " " . $load[1] . " " . $load[2] . "</p>";

    // Get uptime
    $uptime = shell_exec("uptime -p");

    require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/views/mondata.html";
?>