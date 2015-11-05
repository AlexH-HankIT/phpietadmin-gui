<?php
require_once "config.php";
require_once "functions.php";

checkEnvironment();

if ($config['userInput']['action'] === "update") {
    updateInstallation();
} else if ($config['userInput']['action'] === "install") {

} else if ($config['userInput']['action'] === "reinstall") {

}