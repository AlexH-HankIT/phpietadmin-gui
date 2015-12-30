<?php
require_once __DIR__ . '/../app/core/const.inc.php';

function random_password( $length = 8 ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$password = substr( str_shuffle( $chars ), 0, $length );
	return $password;
}

function dialog ($args) {
	$pipes = array (NULL, NULL, NULL);
	// Allow user to interact with dialog
	$in = fopen ('php://stdin', 'r');
	$out = fopen ('php://stdout', 'w');
	// But tell PHP to redirect stderr so we can read it
	$p = proc_open ('dialog '.$args, array (
		0 => $in,
		1 => $out,
		2 => array ('pipe', 'w')
	), $pipes);
	// Wait for and read result
	$result = stream_get_contents ($pipes[2]);
	// Close all handles
	fclose ($pipes[2]);
	fclose ($out);
	fclose ($in);
	proc_close ($p);
	// Return result
	return $result;
}

if (file_exists(AUTH_FILE)) {
	dialog('--title "Auth code" --msgbox "\nAuth code was already generated: "' . file_get_contents(AUTH_FILE) . ' 14 60');
} else {
	$password = random_password(8);
	if (file_put_contents(AUTH_FILE, $password) !== false) {
		dialog('--title "Auth code" --msgbox "\nThanks for installing phpietadmin.\n\nThis is your auth code: ' . $password . '\n\nPress OK and copy it to your clipboard.\n\nThen start your favorite browser and open http://$(hostname -f)/phpietadmin" 14 60');
		chown(AUTH_FILE, 'www-data');
	} else {
		dialog('--title "Auth code" --msgbox "\nThanks for installing phpietadmin.\n\nCan\'t generate an auth code. Please check the permissions and open an issue on github, if the error is persistent." 14 60');
	}
}