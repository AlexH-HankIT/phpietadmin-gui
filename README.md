# phpietadmin
Phpietadmin is an easy to use webinterface to control the iet daemon (http://sourceforge.net/projects/iscsitarget/) written in php and javascript.

## Intention
The main reason for developing this was, to create a way to configure the daemon while it’s in use. The iet daemon reads
the config file only at start/restart. Changes after the daemon was started are only possible via the ietadm command line
tool. This tool alters the configuration of the daemon while it’s running. Unfortunatly, the changes are not reflected
in the config file itself. Which means, if the daemon gets restart the changes made via ietadm are gone, because the
daemons loads only the targets from the config file. Phpietadmin saves all changes in the config file and passes them
directly to the daemon via php’s „exec“ function. This prevents any inconsistency between the config file and the
daemon live config.

## Compatibility
Phpietadmin is tested on 8. But it’s not limited to debian.
It should run just fine on any other linux distribution.

## Docs
* https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v05
* https://github.com/MrCrankHank/phpietadmin/wiki/Update-v04-to-v05

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v05

## Features
Take a look at the github releases for detailed information about the features.

## Bugs in 0.5.3:
    - [x] Reload page after session disconnect
    - [x] Delete lun: check if line contains default parameter
    - [x] Service add menu editing a service does not work
    - [x] Select all checkbox in LVM snapshot delete does not work
    - [x] ACLs are not displayed in "Delete ACL" menu
    - [x] Config menu editing does not work
    - [x] Shutdown/Restart does not work
    - [x] LVM extend does not work
    - [ ] Forced delete of a target does not work if a "ALL" acl is configured
    - [ ] Mousover to show passwords in /phpietadmin/ietusers does not work
    - [ ] Password generator qtip does not work in /phpietadmin/ietusers
    - [ ] add_iqn_to_file() function might remove comments

## Roadmap
In version 0.6:
* LVM
    - [x] Lvm snapshots (No merging...)
    - [x] Add lvextend, lvremove, lvrename features

* Frontend
    - [x] Disable the auto logout by using a 0 in the config menu
    - [x] Put hostname in title
    - [x] Bar for PV/VG usage
    - [x] Cool sliders (http://www.jqueryrain.com/?ot4e1H_o)
    - [x] Better counter (html5 number doesn't look so good... http://www.virtuosoft.eu/code/bootstrap-touchspin/)
    - [x] Nested tables for iet volumes and iet sessions (https://github.com/wenzhixin/bootstrap-table-examples/blob/master/options/sub-table.html)
    - [x] Override option, if user is already logged in
    - [x] Create logging gui
    - [x] Improved ajax menu with error handling
    - [x] Use custom data attributes to store data in dom
    - [x] Configure target: Show if target has open sessions
    - [x] Rework the javascript code
    - [x] Select all checkbox
    - [ ] Release "compressed" javascript files

* Backend
    - [x] Create a target model with all functions which are necessary to add/delete/change a target
    - [x] Create a lvm model
    - [x] Basic error logging
    - [x] Login/Logout logging
    - [x] Debug logging
    - [x] Use namespaces with basic autoloader
    - [x] New lsblk parser
    - [x] Replace version file with .json
    - [x] Use bcrypt for storing passwords
    - [x] Prevent comments from being deleted, when editing a config file
    - [x] Rewrite file parser for the iet files
    - [x] New session implementation
    - [x] Delete the "Detach/delete lun" functions
    - [ ] Write phpietadmin-cli
    - [ ] Database error log
    - [ ] Log also successful messages
    - [ ] Rework controller/models
        - [x] Targets
        - [x] Users
        - [x] User model
        - [x] Config
        - [x] Config model
        - [ ] Database model
        - [ ] Dashboard
        - [ ] Overview
        - [ ] Objects
        - [ ] LVM
        - [ ] Services

        - [ ] PHPietadmin user/session menu
        - [ ] Stop/Reboot/Logout
    - [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
    - [ ] Prevent iqn and objects from having whitespaces
    - [ ] Delete Services/Add menu (Allow only a few services)

* Misc
    - [ ] Create development branch after release of v0.6 (master should be stable)

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
    - [ ] Improve configure target settings menu
    - [ ] Improve nested table row handling
    - [ ] Add target and lvm name to url

* Backend
    - [ ] Write process class to execute commands in the background (+ jwindow)
    - [ ] Create complete documentation on https://readthedocs.org/
    - [ ] Use unity testing
    - [ ] Replace all error codes with exceptions
    - [ ] Support "All" permission in ietd config files
    - [ ] User Model: Delete user, develope better way to check if user is in use

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

Items are completely random ;-)

If you have any problems, please open an issue!