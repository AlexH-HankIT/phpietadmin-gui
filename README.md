# phpietadmin
Phpietadmin is an easy to use webinterface to control the iet daemon (http://sourceforge.net/projects/iscsitarget/) written in php and javascript.

## Intention
The main reason for developing this was, to create a way to configure the daemon while it’s in use. The iet daemon reads
the config file only at start/restart. Changes after the daemon was started are only possible via the ietadm command line
tool. This tool alters the configuration of the daemon while it’s running. Unfortunatly, the changes are not reflected
in the config file itself. Which means, if the daemon gets restart the changes made via ietadm are gone, because the
daemons loads only the targets from the config file. Phpietadmin saves all changes in the config file and passes them
directly to the daemon via php’s „exec“ function. This prevents any inconsistency between the config file and the
daemon live config. The file parsers will preserve all comments, so this tool won't mess up your config files. Also a
backup is created before changes are made.

## Compatibility
Phpietadmin is tested on 8. Basically it should work on every linux distribution with php 5.6 >= and apache2.
Official support for CentOS is planned.

## Screens
* https://github.com/HankIT/phpietadmin-gui/wiki/Screens-v0.6.1

## Docs
* https://github.com/HankIT/phpietadmin-gui/wiki/Installation-v0.6.1
* https://github.com/HankIT/phpietadmin-gui/wiki/Update-v0.6-to-v0.6.1

## Features
Take a look at the github releases for detailed information about the features.

## Roadmap
In version 0.7:
* LVM
    - [ ] Volume group menu (select which volume groups phpietadmin should use)
    - [ ] Optional lv prefix (append LV_ or some other user chosen string)
    - [ ] Add snapshot merge feature to gui
    - [ ] Add enable/disable logical volume feature to gui

* Frontend
    - [ ] Use jwindow to dynamically display the status of running commands
    - [ ] Drag & Drop with HTML5
    - [ ] Menu to import orphaned objects into database
    - [ ] Display input validation with bootstrap css Validation states (http://formvalidation.io/validators/integer/)
    - [ ] Bootstrap-table Table Select Checkbox
    - [ ] Awesome checkboxes (https://github.com/designmodo/Flat-UI)
    - [ ] Improve nested table row handling
    - [ ] Add target and lvm name to url
    - [ ] Overview/Logical volumes VG selector
    - [ ] Add "Edit file directly" option
    - [ ] Searchable table for overview menu
    - [ ] Release "compressed" javascript files

* Backend
    - [ ] Write process class to execute commands in the background (+ jwindow)
    - [ ] Create complete documentation on https://readthedocs.org/
    - [ ] Use unity testing
    - [ ] Replace all error codes with exceptions
    - [ ] Support "All" permission in ietd config files
    - [ ] Write phpietadmin-cli
    - [ ] Database error log

* In version 0.8:
    - [ ] Support for DRBD (show status)
    - [ ] Support for HA Clusters (Corosync & Pacemaker, only for iet)

* In version 0.9:
    - [ ] Support for nfs

## More
- [ ] Software raid status
- [ ] Support for samba shares
- [ ] Show and configure network settings
- [ ] Enable/Disable features
- [ ] Support for apcupsd
- [ ] Manual selection of block devices
- [ ] HDD temp
- [ ] Pie Chart for volume groups
- [ ] Smart data
- [ ] Backup config files (http://code.stephenmorley.org/php/diff-implementation/)
- [ ] Menu to restore config files
- [ ] function naming convention in models (prepend class name to function name)
- [ ] Create "consistency", which displays if the daemon config and the config file are identically
- [ ] Use composer
- [ ] Use json for tables
- [ ] Change duplication check (Try to select the specific value from the database)
- [ ] Use own exception class for error handling
- [ ] Sign archives
- [ ] Separate database models
- [ ] SSH login via web gui (https://github.com/liftoff/GateOne)
- [ ] Add bar to snapshot delete gui

If you have any problems, please open an issue!