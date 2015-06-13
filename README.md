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
- [ ] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [ ] Handle no PV/VG/LV error
- [ ] Delete lun: Display if target is in use
- [x] Delete target: Display if target is in use (Targets with connections aren't displayed at all)
- [ ] Delete target: Checkbox to force deletion, even if in use (Disconnect initiator and delete target)

## Planned features
In version 0.4:
- [x] Add dashboard (Uptime, Load, Mem Usage, Interfaces...)
- [x] Delete initiators.deny since it's deprecated
- [x] Use require.js to organize javascript files
- [x] Add version check
- [x] Add targets without session to Overview/ietsessions
- [x] Change password for user admin via gui
- [x] Select multiple Objects/Users
- [x] Delete all options with target if deleted (Except luns, target with luns cannot be deleted)
- [x] Validate chap password length (min 12, max 16)
- [x] Add incoming/outgoing user authentication
- [x] Check if chap user is already added
- [x] Check if object is already added
- [x] Delete User: Check if it's in use
- [x] Add incoming/outgoing discovery user authentication
- [ ] Create install and update documentation
- [ ] Support for Jessie (Tests...)

More:
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [ ] Software raid status
- [ ] Exclude volume group
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)
- [ ] Add delete button to sessions
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for DRBD
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [ ] Support for nfs
- [ ] Support for apcupsd

Items are completely random ;-)

If you have any problems, please open an issue!