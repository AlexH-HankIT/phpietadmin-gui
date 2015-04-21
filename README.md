# phpietadmin
Webinterface to control the iet daemon written in php using mvc pattern.

### Features
- [x] Overview disks, iet volumes, iet sessions, all typs of lvm volumes, initiator and target permissions
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
- [ ] Handle duplicated names of logical volumes
- [ ] Output error messages from exec
- [x] Show all available lvm volumes in one dropdown menu (targets add)
- [x] Delete all permissions of target if it is deleted
- [ ] Use ajax for post requests
- [ ] Add php sessions timeout
- [x] Add service running/not running to footer
- [ ] Add console output to service menu
- [ ] Write installation documentation
- [ ] Create screenshots and document the features
- [ ] Don't reload menu and footer
- [x] Dropdown for fileio or blockio
- [x] Dropdown for mode (write through/read only)

## Planned features
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Lvm snapshots
- [ ] Allow->Target->Add/Delete
- [ ] Software raid status
- [ ] Exclude volume group
- [ ] Add multiple luns to one target
- [ ] Add incoming user authentication for target
- [ ] Add incoming global user authentication
- [ ] Add config menu to gui

Items are completely random ;-)

If you have any problems, please open an issue!
