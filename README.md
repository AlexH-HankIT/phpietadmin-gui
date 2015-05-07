# phpietadmin
Webinterface to control the iet daemon written in php using mvc pattern.

## Docs
https://github.com/MrCrankHank/phpietadmin/wiki/Installation-v02

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
- [x] Delete all permissions of target if it is deleted
- [x] Use ajax for post requests
- [ ] Add php sessions timeout
- [x] Add service running/not running to footer
- [x] Add console output to service menu
- [x] Write installation documentation
- [x] Create screenshots
- [ ] Document the features
- [ ] Don't reload menu and footer
- [x] Dropdown for fileio or blockio
- [x] Dropdown for mode (write through/read only)
- [ ] Handle no PV/VG/LV error
- [x] Handle multiple sessions for one target
- [x] Handle multiple luns for one target

## Planned features
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [ ] Allow->Target->Add/Delete
- [ ] Software raid status
- [ ] Exclude volume group
- [x] Add multiple luns to one target
- [ ] Manual selection of block devies (input menu already implemented, but logic is missing)
- [ ] Add incoming user authentication for target
- [ ] Add incoming global user authentication
- [ ] Add config menuto gui
- [ ] Add delete button to sessions
- [x] Split 'Add target' in add target and map lun
- [ ] Add dashboard (Uptime, Load, Mem Usage, Interfaces...)
- [ ] Support for HA Clusters (Corosync & Pacemaker) <-- hard one

Items are completely random ;-)

If you have any problems, please open an issue!
