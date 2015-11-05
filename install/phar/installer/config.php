<?php
$baseDir = "/usr/share/phpietadmin";
$appDir = $baseDir . "/app";
$installDir = $baseDir . '/install';

$config = array(
    'databaseScriptNew' => $installDir . "/database.new.sql",
    'databaseScriptUpdate' => $installDir . "/database.update.sql",
    'databaseFile' => $appDir . "/config.db",
    'authFile' => $installDir . "/auth"
);