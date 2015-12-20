<?php
require_once __DIR__ . '/../app/core/const.inc.php';

function random_password( $length = 8 ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$password = substr( str_shuffle( $chars ), 0, $length );
	return $password;
}

if (file_exists(AUTH_FILE)) {
	echo "Code was already generated!\n";
} else {
	$password = random_password(8);
	if (file_put_contents(AUTH_FILE, $password) !== false) {
		echo "Code is " . $password . "\nOpen your new phpietadmin installation and create a user.\n";
		chown(AUTH_FILE, 'www-data');
	} else {
		echo "Failure\n";
	}
}