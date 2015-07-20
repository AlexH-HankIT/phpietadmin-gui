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
Phpietadmin is tested on Debian 7 and 8. But it’s not limited to Debian.
It should run just fine on any other linux distribution.

## Docs
* https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v05
* https://github.com/MrCrankHank/phpietadmin/wiki/Update-v04-to-v05

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v05

## Features
Take a look at the github releases for detailed information about the features.

## Bugs in the beta of v0.5 will be fixed in v0.5.1. So far:
[ ] Jquery/javascript is sometimes not executed, when loaded via ajax
[ ] Target acl cannot be deleted if no initiator acl exists (Page isn't displayed, only error)
[ ] Discovery users are always already added, even if not
[ ] Config menu update migth not work
[ ] A few unnecessary page reloads

## ToDo
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Software raid status
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
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
- [ ] Login/Logout logging
- [ ] Backup config files (http://code.stephenmorley.org/php/diff-implementation/)
- [ ] Menu to restore config files
- [ ] Action logging
- [ ] Document already written methods

## Planned features and todo
In version 0.6:
- [ ] Volume group menu (select which volume groups phpietadmin should use)
- [ ] Config -> MISC -> Idle, no zeros
- [ ] Own model for exec's
- [ ] Pie Chart for volume groups
- [ ] Prevent comments from being deleted, when editing a config file
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [ ] Support for nfs
- [ ] Lvm snapshots

Items are completely random ;-)

If you have any problems, please open an issue!
