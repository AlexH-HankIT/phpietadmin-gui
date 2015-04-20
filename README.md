# phpietadmin
Webinterface to control the iet daemon written in php using mvc pattern.

## ToDo
- [x] Complete switch to mvc
- [ ] Add config menu to gui
- [ ] Use more javascript
- [ ] Software raid status
- [ ] Exclude volume group
- [ ] Allow->Target->Add/Delete
- [ ] Handle "Device or resource busy" error when trying to delete a target in use (Don't display targets in use for deletion)
- [ ] Don't display targets in use for permission deletion
- [ ] Handle duplicated names of logical volumes
- [ ] Output error messages from exec
- [ ] Show all available lvm volumes in one dropdown menu (targets add/delete)
- [x] Delete all permissions of target if it is deleted
- [ ] Add lvextend, lvremove, lvrename features
- [ ] Use ajax for post requests
- [ ] Add php sessions
- [ ] Add incoming user authentication for target
- [ ] Add incoming global user authentication
- [x] Add service running/not running to footer
- [ ] Add console output to service menu
- [ ] Write installation documentation
- [ ] Create screenshots and document the features
- [ ] Don't reload menu and footer
- [ ] Add multiple luns to one target
- [x] Dropdown for fileio or blockio
- [x] Dropdown for mode (write through/read only)

Items are completely random ;-)

If you have any problems, please open an issue!
