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
Phpietadmin is tested on Debian 7. A version running on Debian 8 will be released soon.
But it’s not limited to Debian. It should run just fine on other linux distributions.

## Docs
https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v03

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v02

## Features
Take a look at the github releases for detailed information about the features.

## ToDo
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Don't display targets in use for permission deletion
- [x] Delete all permissions of target if it is deleted
- [ ] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [ ] Document the features
- [ ] Handle no PV/VG/LV error
- [ ] Delete lun: Display if target is in use
- [ ] Delete target: Display if target is in use
- [ ] Delete target: Checkbox to force deletion, even if in use
- [ ] Remove html onclick elements

## Planned features
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [ ] Software raid status
- [ ] Exclude volume group
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)
- [ ] Add incoming user authentication for target
- [ ] Add incoming global user authentication
- [ ] Add delete button to sessions
- [ ] Add dashboard (Uptime, Load, Mem Usage, Interfaces...)
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for DRBD
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [ ] Support nfs

Items are completely random ;-)

If you have any problems, please open an issue!
