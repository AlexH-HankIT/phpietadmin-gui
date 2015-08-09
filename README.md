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
Phpietadmin is tested on 8. But it’s not limited to Debian.
It should run just fine on any other linux distribution.

## Docs
* https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v05
* https://github.com/MrCrankHank/phpietadmin/wiki/Update-v04-to-v05

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v05

## Features
Take a look at the github releases for detailed information about the features.

## Since v0.5 has a few design issues, the bugs will all be fixed with version v0.6
## Bugs in the beta of v0.5:
## So far:
- [x] Jquery/javascript is sometimes not executed, when loaded via ajax
- [ ] Target acl cannot be deleted if no initiator acl exists (Page isn't displayed, only error)
- [x] Discovery users are always already added, even if not
- [ ] Adding of multiple discovery users to the daemon might fail
- [x] Config menu update might not work
- [ ] A few unnecessary page reloads
- [ ] Installer isn't working <- should be fixed, to be tested
- [x] Filter table jquery plugin displays hidden rows when searching
- [ ] Login not possible under yet unknown conditions
- [ ] Cron job to regularly purge session data
- [x] Password of discovery user max 12 chars
- [ ] Enable a few special chars in Targets/Add
- [ ] Fix dependencies between models
    * Ietaddtarget->ietvolumes->parse_proc_volumes()
    * IetVolumes->Ietaddtarget->get_proc_volume_content()

## Planned features and todo
In version 0.6:
* LVM
    - [x] Lvm snapshots
    - [x] Add lvextend, lvremove, lvrename features
    - [ ] Volume group menu (select which volume groups phpietadmin should use)
    - [ ] Optional lv prefix (append LV_ or some other user chosen string)

* IET
    - [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
    - [ ] Create "consistency", which displays if the daemon config and the config file are identically

* Frontend
    - [ ] Config -> MISC -> Idle, no zeros
    - [ ] Pie Chart for volume groups
    - [x] Put hostname in title
    - [ ] Bar for volume group usage

* Backend
    - [ ] Prevent comments from being deleted, when editing a config file
    - [ ] Use json for ajax responses
    - [ ] Replace multiple !empty with one mempty() function
    - [ ] When checking for post data, always check with isset() and then with mempty()/empty()
    - [ ] Use try/catch for error handling
    - [ ] Use Type Hinting (http://php.net/manual/de/language.oop5.typehinting.php)
    - [x] Create a target model with all functions which are necessary to add/delete/change a target
    - [ ] Create a lvm model
    - [ ] Write phpietadmin-cli
        - [ ] Install/Update
        - [ ] Import orphaned objects into database
    - [ ] Error logging
    - [ ] Login/Logout logging
    - [ ] Use namespaces with autoloading

* Docs
    - [ ] Document already written methods (phpDoc)
        - [ ] auth.php
        - [ ] config.php
        - [ ] connection.php
        - [ ] dashboard.php
        - [ ] ietusers.php
        - [ ] lvm.php
        - [ ] objects.php
        - [ ] overview.php
        - [ ] permission.php
        - [ ] service.php
        - [ ] targets.php
        - [ ] App.php
        - [ ] Controller.php
        - [ ] Database.php
        - [ ] Disk.php
        - [x] Exec.php
        - [ ] Ietaddtarget.php
        - [ ] Ietdelete.php
        - [ ] Ietpermissions.php
        - [ ] IetSessions.php
        - [ ] IetVolumes.php
        - [ ] Lvmdisplay.php
        - [ ] Regex.php
        - [ ] Session.php
        - [ ] Settings.php
        - [ ] Std.php

## More
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Software raid status
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for nfs
- [ ] Support for DRBD
- [ ] Support for samba shares
- [ ] Show and configure network settings
- [ ] Enable/Disable features
- [ ] Delete lun: Display if target is in use
- [ ] Support for apcupsd
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)
- [ ] HDD temp
- [ ] Smart data
- [ ] Config option for production and development
    * Production:
        * minifized html and javascript generated at the relase will be used
    * Development
        * normal versions will be used
- [ ] Backup config files (http://code.stephenmorley.org/php/diff-implementation/)
- [ ] Menu to restore config files

Items are completely random ;-)

If you have any problems, please open an issue!