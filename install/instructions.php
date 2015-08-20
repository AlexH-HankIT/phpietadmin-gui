<?php

class Instructions extends Logic {
    protected function __construct($phpietadmin_base_dir = '/usr/share/phpietadmin') {
        parent::__construct($phpietadmin_base_dir);
    }

    protected function update() {
        $this->extract_phpietadmin_files();
        $this->copy_phpietadmin_installation();
        $this->update_package_database();
        $this->install_packages();
        $this->symlink_sudoer_file();
        $this->change_ietd_permission();
        $this->symlink_phpietadmin_apache_config();
        $this->restart_apache();
        $this->edit_database('update');
        $this->delete_temp_phpietadmin_dir();
    }

    protected function install() {
        $this->extract_phpietadmin_files();
        $this->copy_phpietadmin_installation();
        $this->update_package_database();
        $this->install_packages();
        $this->symlink_sudoer_file();
        $this->change_ietd_permission();
        $this->enable_apache_mods();
        $this->sanitize_config_files();
        $this->delete_default_apache_config();
        $this->symlink_phpietadmin_apache_config();
        $this->restart_apache();
        $this->restart_ietd();
        $this->edit_database('install');
        $this->generate_auth_code();
        $this->delete_temp_phpietadmin_dir();
    }

    protected function backup() {

    }

    protected function restore() {

    }

    protected function remove() {
        $this->delete_phpietadmin_dir();
        $this->delete_phpietadmin_config_files();
        $this->purge_packages();
    }
}