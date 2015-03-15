; <?php exit; ?>

[iet]
proc_sessions="/proc/net/iet/session"
proc_volumes="/proc/net/iet/volume"
ietd_config_file='/etc/iet/ietd.conf'
ietd_init_allow="/etc/iet/initiators.allow"
ietd_target_allow="/etc/iet/targets.allow"
ietadm="/usr/sbin/ietadm"
servicename="iscsitarget"
iqn="iqn.2014-12.com.example.iscsi"

[lvm]
lvs="/sbin/lvs"
vgs="/sbin/vgs"
pvs="/sbin/pvs"
lvcreate="/sbin/lvcreate"
lvreduce="/sbin/lvreduce"
lvextend="/sbin/lvextend"
lvremove="/sbin/lvremove"

[mdraid]
mdstat="/proc/mdstat"

[misc]
sudo="/usr/bin/sudo"
service="/usr/sbin/service"
lsblk="/bin/lsblk"