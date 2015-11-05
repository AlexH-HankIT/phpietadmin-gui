<?php
$phar = new Phar('phpietadmin-installer.phar', 0, 'phpietadmin-installer');
print_r($phar->buildFromDirectory("installer"));