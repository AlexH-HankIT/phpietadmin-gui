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
	if (file_put_contents($authFile, random_password(8)) !== false) {
		echo "Success\n";
	} else {
		echo "Failure\n";
	}
}