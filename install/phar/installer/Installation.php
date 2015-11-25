<?php
class Installation extends Base {
    public function createInstallation() {
        $this->installPackages();
        $this->configureApache();
        $this->configureSudo();
        $this->configureietd();
        $this->copyInstallation($this->baseDir);
        $this->createFolders();
        $this->createDatabase();
        $this->setPermissions();
        $this->generateAuthCode();
    }

    private function createFolders() {
        if (!dir($this->config['directories']['backups'])) {
            mkdir($this->config['directories']['backups']);
        }
        if (!dir($this->config['directories']['log'])) {
            mkdir($this->config['directories']['log']);
        }
    }

    private function setPermissions() {
        foreach ($this->config['directories'] as $dir) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            foreach($iterator as $item) {
                chmod($item, 0770);
                chown($item, 'root');
                chgrp($item, 'www-data');
            }
        }
    }

    private function createDatabase() {
        try {
            if (file_exists($this->config['databaseScriptNew'])) {
                exec("sqlite3 " . $this->config['databaseFile'] . "< " . $this->config['databaseScriptNew'], $output, $return);

                if ($return !== 0) {
                    throw new exception("Can't update the database");
                }
            } else {
                throw new exception("Can't find the database update file!");
            }
        } catch (Exception $e) {
            $this->showErrorMessage($e->getMessage());
        }
    }

    private function installPackages() {
        exec("apt-get update", $output, $return);
        exec("apt-get -y install " . implode(" ", $this->config['packages']), $output, $return);
    }

    private function configureApache() {
        if ($this->config['userInput']['configureApache'] === true) {

        }
    }

    private function configureSudo() {
        if ($this->config['userInput']['configureSudo'] === true) {

        }
    }

    private function configureietd() {
        if ($this->config['userInput']['configureIetd'] === true) {

        }
    }
}