## Roadmap
This is just a approximately of the features, i want to implement in the future.
In version 0.7:
* LVM
    - [ ] Volume group menu (select which volume groups phpietadmin should use)
    - [ ] Optional lv prefix (append LV_ or some other user chosen string)
    - [ ] Add snapshot merge feature to gui
    - [ ] Add enable/disable logical volume feature to gui

* Frontend
    - [ ] Use jwindow to dynamically display the status of running commands
    - [ ] Drag & Drop with HTML5
    - [ ] Menu to import orphaned objects into database
    - [ ] Display input validation with bootstrap css Validation states (http://formvalidation.io/validators/integer/)
    - [ ] Bootstrap-table Table Select Checkbox
    - [ ] Awesome checkboxes (https://github.com/designmodo/Flat-UI)
    - [ ] Improve nested table row handling
    - [ ] Add target and lvm name to url
    - [ ] Overview/Logical volumes VG selector
    - [ ] Add "Edit file directly" option
    - [ ] Searchable table for overview menu
    - [ ] Release "compressed" javascript files

* Backend
    - [ ] Write process class to execute commands in the background (+ jwindow)
    - [ ] Create complete documentation on https://readthedocs.org/
    - [ ] Use unity testing
    - [ ] Replace all error codes with exceptions
    - [ ] Support "All" permission in ietd config files
    - [ ] Write phpietadmin-cli
    - [ ] Database error log

* In version 0.8:
    - [ ] Support for DRBD (show status)
    - [ ] Support for HA Clusters (Corosync & Pacemaker, only for iet)

* In version 0.9:
    - [ ] Support for nfs

## More
- [ ] Software raid status
- [ ] Support for samba shares
- [ ] Show and configure network settings
- [ ] Enable/Disable features
- [ ] Support for apcupsd
- [ ] Manual selection of block devices
- [ ] HDD temp
- [ ] Pie Chart for volume groups
- [ ] Smart data
- [ ] Backup config files (http://code.stephenmorley.org/php/diff-implementation/)
- [ ] Menu to restore config files
- [ ] function naming convention in models (prepend class name to function name)
- [ ] Create "consistency", which displays if the daemon config and the config file are identically
- [ ] Use composer
- [ ] Use json for tables
- [ ] Change duplication check (Try to select the specific value from the database)
- [ ] Use own exception class for error handling
- [ ] Sign archives
- [ ] Separate database models
- [ ] SSH login via web gui (https://github.com/liftoff/GateOne)
- [ ] Add bar to snapshot delete gui