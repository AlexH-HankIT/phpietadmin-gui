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
- [x] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [x] Delete target: Display if target is in use (Targets with connections aren't displayed at all)

## Planned features
In version 0.5:
- [ ] Iet settings configuration menu
- [x] Shutdown/reboot via gui (Add client side validation, Prevent link from being directly pressed)
    - [x] Check via sweetalert, shutdown/reboot server only if post var isset
- [x] Add delete button to sessions
- [ ] Delete target: Checkbox to force deletion, even if in use (Disconnect initiator and delete target)
- [ ] Own model for exec's
- [ ] Add up2date documentation & screenshots
- [x] Rework service tab
    - [x] Add other services
    - [x] Enable services with checkbox
- [x] Set sudoer permission for service execution
- [ ] Create connection controller
    - [ ] Add server not reachable message if connection fails (use this: https://github.com/jdfreder/pingjs/blob/master/ping.js, http://stackoverflow.com/questions/4282151/is-it-possible-to-ping-a-server-from-javascript)
    - [ ] Check service/services running
    - [ ] Check session expired
    - [ ] Disable apache access logging for this controller (http://stackoverflow.com/questions/10002289/prevent-stop-apache-from-logging-specific-ajax-xmlhttprequests)
- [ ] Pie Chart for volume groups
- [x] Add glyphicons Targets > Add/delete discovery user, Services -> overview, add
- [ ] Check array search for correct value comparing
- [ ] addslashes for every user input
- [ ] htmlspecialchars for every echo

More:
- [ ] Software raid status
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for DRBD
- [ ] Support for samba shares
- [ ] Show and configure network settings
- [ ] Enable/Disable features
- [ ] Delete lun: Display if target is in use
- [ ] Don't display targets in use for permission deletion
- [ ] Lvm snapshots
- [ ] Exclude volume group
- [ ] Support for nfs
- [ ] Support for apcupsd
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)

Items are completely random ;-)

If you have any problems, please open an issue!