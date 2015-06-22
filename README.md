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
* https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v04
* https://github.com/MrCrankHank/phpietadmin/wiki/Update-v03-to-v04

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v02

## Features
Take a look at the github releases for detailed information about the features.

## ToDo
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Don't display targets in use for permission deletion
- [ ] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [ ] Delete lun: Display if target is in use
- [x] Delete target: Display if target is in use (Targets with connections aren't displayed at all)
- [ ] Delete target: Checkbox to force deletion, even if in use (Disconnect initiator and delete target)

## Planned features
In version 0.5:
- [ ] Support for nfs
- [ ] Support for apcupsd
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [ ] Exclude volume group
- [ ] Pie Chart for volume groups
- [ ] Iet settings configuration menu

More:
- [ ] Software raid status
- [ ] Add delete button to sessions
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for DRBD
- [ ] Support for samba shares


Items are completely random ;-)

If you have any problems, please open an issue!
