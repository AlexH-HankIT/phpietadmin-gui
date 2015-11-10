<?php
$authFile = '/usr/share/phpietadmin/install/auth';

function random_password( $length = 8 ) {
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$password = substr( str_shuffle( $chars ), 0, $length );
return $password;
}

if (file_exists($authFile)) {
echo "Code was already generated!\n";
} else {
$password = random_password(8);
if (file_put_contents($authFile, $password) !== false) {
echo "Success\n";
echo "Code is " . $password . "\n";
chown($authFile, 'www-data');
} else {
echo "Failure\n";
}
}