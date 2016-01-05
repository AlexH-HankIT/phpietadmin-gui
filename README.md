# phpietadmin
Phpietadmin is an easy to use webinterface to control the iscsi enterprise target (iet) daemon (http://sourceforge.net/projects/iscsitarget/) written in php and javascript.

## Features
### Dashboard
The dashboard provides a quick overview about your system and phpietadmin.

### iSCSI
phpietadmin enables you to configure all features of the iscsi enterprise target daemon via web, e.g.:

    * Adding/Removing luns
    * Initiator/Target allow
    * User management
    * Sessions
    * Settings
    * Target removal

### LVM
phpietadmin currently only supports lvm as iscsi luns!

    * Adding volumes
    * Extending volumes (+ notify iscsi initator about the change)
    * Shrinking volumes
    * Renaming volumes
    * Deleting volumes

..and more.

## Intention
The main reason for developing this was, to create a way to configure the daemon while it’s in use. The iet daemon reads
the config file only at start/restart. Changes after the daemon was started are only possible via the ietadm command line
tool. This tool alters the configuration of the daemon while it’s running. Unfortunatly, the changes are not reflected
in the config file itself. Which means, if the daemon gets restart the changes made via ietadm are gone, because the
daemons loads only the targets from the config file. Phpietadmin saves all changes in the config file and passes them
directly to the daemon via php’s „exec“ function. This prevents any inconsistency between the config file and the
daemon live config. The file parsers will preserve all comments, so this tool won't mess up your config files. Also a
backup is created before changes are made.

## Compatibility
Phpietadmin is tested on Debian "Jessie" 8. Basically it should work on every linux distribution with php 5.6 >= and apache2.
Official support for CentOS is planned.

## Screens
* https://github.com/HankIT/phpietadmin-gui/wiki/Screens-v0.6.1

## Docs
### Stable
* https://github.com/HankIT/phpietadmin-gui/wiki/Installation-v0.6.1
* https://github.com/HankIT/phpietadmin-gui/wiki/Update-v0.6-to-v0.6.1

### Beta
* https://github.com/HankIT/phpietadmin-doc/wiki/v0.6.2-%5Bbeta%5D

If you have any problems, please open an issue!