<?php
   // Get service status ;-)
   $return = get_service_status($service);

    // Get system load
    $load = sys_getloadavg();
    $out = "<p>Load: " . $load[0] . " " . $load[1] . " " . $load[2] . "</p>";

    // Get uptime
    $data = shell_exec('uptime');
    $uptime = explode(' up ', $data);
    $uptime = explode(',', $uptime[1]);
    $uptime = $uptime[0].', '.$uptime[1];

    require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/views/mondata.html";
?>