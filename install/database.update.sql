DROP TABLE IF EXISTS ietsettings;
CREATE TABLE ietsettings(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  option varchar(50) NOT NULL,
  defaultvalue varchar(50) NOT NULL,
  type varchar(50) NOT NULL,
  state NUMERIC BOOLEAN NOT NULL,
  chars varchar(50),
  othervalue1 varchar(50) DEFAULT NULL
);

DROP TABLE IF EXISTS services;
CREATE TABLE services(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name varchar(50),
  enabled numeric boolean,
  deleteable numeric boolean
);

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  session_id varchar(64) NOT NULL,
  username_id varchar(50) NOT NULL,
  login_time varchar(64) NOT NULL,
  source_ip varchar(15) NOT NULL,
  browser_agent varchar(200) NOT NULL
);

DROP TABLE IF EXISTS volume_groups;
CREATE TABLE volume_groups(
  volume_group_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  volume_group_name varchar(255) NOT NULL
);

INSERT INTO ietsettings (option, defaultvalue, type, state, chars) VALUES
    ('MaxConnections', 1, 'input', 0, 'digits'),
    ('MaxRecvDataSegmentLength', 8192, 'input', 1, 'digits'),
    ('MaxXmitDataSegmentLength', 8192, 'input', 1, 'digits'),
    ('MaxBurstLength', 262144, 'input', 1, 'digits'),
    ('FirstBurstLength', 65536, 'input', 1, 'digits'),
    ('DefaultTime2Wait', 'false', 'input', 0, 'digits'),
    ('DefaultTime2Retain', 0, 'input', 0, 'digits'),
    ('MaxOutstandingR2T', 1, 'input', 1, 'digits'),
    ('ErrorRecoveryLevel', 0, 'input', 0, 'digits'),
    ('NOPInterval', 'false', 'input', 1, 'digits'),
    ('NOPTimeout', 'false', 'input', 1, 'digits'),
    ('Wthreads', 8, 'input', 1, 'digits'),
    ('QueuedCommands', 32, 'input', 1, 'digits');

INSERT INTO ietsettings (option, defaultvalue, type, state, othervalue1) VALUES
  ('HeaderDigest', 'None', 'select', 1, 'CRC32C'),
  ('DataDigest', 'None', 'select', 1, 'CRC32C'),
  ('InitialR2T', 'Yes', 'select', 1, 'No'),
  ('ImmediateData', 'No', 'select', 1, 'Yes'),
  ('DataPDUInOrder', 'Yes', 'select', 0, 'No'),
  ('DataSequenceInOrder', 'Yes', 'select', 0, 'No');

DELETE FROM config;
INSERT INTO config (option, optioningui, ispath, value, description, category) VALUES
    ('iqn', 'IQN', 0, 'iqn.2014-12.com.example.iscsi', "Names of the iscsi targets", 1),
    ('proc_sessions', '/proc session', 1, '/proc/net/iet/session', "Path to the IET sessions file", 1),
    ('proc_volumes', '/proc volume', 1, '/proc/net/iet/volume', "Path to the IET volumes file", 1),
    ('ietd_config_file', 'IET config file', 1, '/etc/iet/ietd.conf', "Path to the IET config file", 1),
    ('ietd_init_allow', 'IET initiator allow', 1, '/etc/iet/initiators.allow', "Path to the IET initiators allow file", 1),
    ('ietd_target_allow', 'IET target allow', 1, '/etc/iet/targets.allow', "Path to the IET targets allow file", 1),
    ('ietadm', 'ietadm bin', 1, '/usr/sbin/ietadm', "Path to the IET admin tool", 4),
    ('lvs', 'lvs bin', 1, '/sbin/lvs', "Path to the lvs binary", 4),
    ('vgs', 'vgs bin', 1, '/sbin/vgs', "Path to the vgs binary", 4),
    ('pvs', 'pvs bin', 1, '/sbin/pvs', "Path to the pvs binary", 4),
    ('lvcreate', 'lvcreate bin', 1, '/sbin/lvcreate', "Path to the lvcreate binary", 4),
    ('lvrename', 'lvrename bin', 1, '/sbin/lvrename', "Path to the lvrename binary", 4),
    ('lvreduce', 'lvreduce bin', 1, '/sbin/lvreduce', "Path to the lvreduce binary", 4),
    ('lvextend', 'lvextend bin', 1, '/sbin/lvextend', "Path to the lvextend binary", 4),
    ('lvremove', 'lvremove bin', 1, '/sbin/lvremove', "Path to the lvremove binary", 4),
    ('lvconvert', 'lvconvert bin', 1, '/sbin/lvconvert', "Path to the lvconvert binary", 4),
    ('prefix', 'Prefix', 0, '', 'Prefix for newly created logical volumes', 2),
    ('mdstat', '/proc mdstat', 1, '/proc/mdstat', "Path to the mdstat file", 3),
    ('sudo', 'subo bin', 1, '/usr/bin/sudo', "Path to the sudo binary", 4),
    ('service', 'service bin', 1, '/usr/sbin/service', "Path to the service binary", 4),
    ('lsblk', 'lsblk bin', 1, '/bin/lsblk', "Path to the lsblk binary", 4),
    ('shutdown', 'shutdown bin', 1, '/sbin/shutdown', "Path to the shutdown binary", 4),
    ('idle', 'idle time', 0, 15, 'Time until the user is automatically logged out in minutes', 3);

INSERT INTO category (category) VALUES ('bin');

INSERT INTO services (name, enabled, deleteable) VALUES
  ('cron', 1, 1),
  ('ssh', 1, 1),
  ('drbd', 0, 1),
  ('puppet', 0, 1),
  ('iscsitarget', 1, 1),
  ('nfs-kernel-server', 0, 0),
  ('nagios-nrpe-server', 0, 1),
  ('rsyslog', 1, 1),
  ('apache2', 1, 0),
  ('nullmailer',0, 1),
  ('exim4', 0, 1),
  ('corosync', 0, 1),
  ('pacemaker', 0, 1),
  ('apcupsd', 0, 1),
  ('smbd', 0, 1),
  ('nmbd', 0, 1);

DELETE FROM config where option='servicename';