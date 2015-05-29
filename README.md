# phpietadmin
Webinterface to control the iet daemon written in php using mvc pattern.

## Docs
https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v03

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v02

## Features
Take a look at the github releases for detailed information about the features.

## ToDo
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Don't display targets in use for permission deletion
- [ ] Delete all permissions of target if it is deleted
- [ ] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [ ] Document the features
- [ ] Handle no PV/VG/LV error
- [ ] Delete lun: Display if target is in use
- [ ] Delete target: Display if target is in use
- [ ] Delete target: Checkbox to force deletion, even if in use
- [ ] Remove html onclick elements
- [ ] Check if user is already logged in

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

Items are completely random ;-)

If you have any problems, please open an issue!
