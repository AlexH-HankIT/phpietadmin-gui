# phpietadmin
Webinterface to control the iet daemon written in php using mvc pattern.

## Docs
https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v03

## Screens
https://github.com/MrCrankHank/phpietadmin/wiki/Screens-v02

## Features
- [x] Overview about disks, iet volumes, iet sessions, all typs of lvm volumes, initiator and target permissions
- [x] Add/Delete iSCSI targets
- [x] Add/Delete initiator allow
- [x] Add/Delete logical lvm volumes
- [x] Start/Stop/Restart iet service
- [x] Auth via php sessions

## ToDo
- [x] Complete switch to mvc
- [ ] Use more javascript
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Don't display targets in use for permission deletion
- [x] Handle duplicated names of logical volumes
- [x] Output error messages from exec
- [x] Show all available lvm volumes in one dropdown menu (targets add)
- [ ] Delete all permissions of target if it is deleted
- [x] Use ajax for post requests
- [ ] Add php sessions timeout
- [ ] Check if user 'admin' is already logged in
- [x] Add service running/not running to footer
- [x] Add console output to service menu
- [x] Write installation documentation
- [x] Create screenshots
- [ ] Document the features
- [x] Dropdown for fileio or blockio
- [x] Dropdown for mode (write through/read only)
- [ ] Handle no PV/VG/LV error
- [x] Handle multiple sessions for one target
- [x] Handle multiple luns for one target
- [ ] Delete lun: Display if target is in use
- [ ] Delete target: Display if target is in use
- [ ] Delete target: Checkbox to force deletion, even if in use
- [ ] Remove html onclick elements

## Planned features
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [x] Allow->Target->Add/Delete
- [ ] Software raid status
- [ ] Exclude volume group
- [x] Add multiple luns to one target
- [ ] Manual selection of block devices (input menu already implemented, but logic is missing)
- [ ] Add incoming user authentication for target
- [ ] Add incoming global user authentication
- [x] Add config menuto gui
- [ ] Add delete button to sessions
- [x] Split 'Add target' in add target and map lun
- [ ] Add dashboard (Uptime, Load, Mem Usage, Interfaces...)
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one
- [ ] Support for DRBD
- [ ] Support for live resizing of targets (with workaround, since iet doesn't support)
- [x] Add objects menu for allow rules (host, network, iqn)

Items are completely random ;-)

If you have any problems, please open an issue!
