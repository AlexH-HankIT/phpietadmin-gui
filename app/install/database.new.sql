DROP TABLE IF EXISTS config;
CREATE TABLE config(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  option VARCHAR(50) NOT NULL,
  optioningui VARCHAR(50) NOT NULL,
  ispath BOOLEAN NOT NULL DEFAULT 1,
  value VARCHAR(50) NOT NULL,
  editable_via_gui BOOLEAN NOT NULL DEFAULT 1,
  description VARCHAR(200),
  category INT NOT NULL
);

DROP TABLE IF EXISTS category;
CREATE TABLE category(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  category varchar(20)
);

DROP TABLE IF EXISTS user;
CREATE TABLE user(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(64) NOT NULL /* for sha256 hash */
);

DROP TABLE IF EXISTS objects;
CREATE TABLE objects(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  type_id INTEGER NOT NULL,
  value varchar(50) NOT NULL,
  name varchar(50) NOT NULL
);

DROP TABLE IF EXISTS types;
CREATE TABLE types(
  type_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  display_name varchar(10) NOT NULL,
  value varchar(10) NOT NULL
);

DROP TABLE IF EXISTS ietusers;
CREATE TABLE ietusers(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL
);

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
  enabled numeric boolean
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

INSERT INTO ietsettings (option, defaultvalue, type, state, chars) VALUES
    ('Alias', 'false', 'input', 1, 'all'),
    ('MaxConnections', 1, 'input', 0, 'digits'),
    ('MaxSessions', 1, 'input', 1, 'digits'),
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

INSERT INTO config (option, optioningui, ispath, value, description, category) VALUES
    ('iqn', 'IQN', 0, 'iqn.2014-12.com.example.iscsi', "Names of the iscsi targets", 1),
    ('proc_sessions', '/proc session', 1, '/proc/net/iet/session', "Path to the IET sessions file", 1),
    ('proc_volumes', '/proc volume', 1, '/proc/net/iet/volume', "Path to the IET volumes file", 1),
    ('ietd_config_file', 'IET config file', 1, '/etc/iet/ietd.conf', "Path to the IET config file", 1),
    ('ietd_init_allow', 'IET initiator allow', 1, '/etc/iet/initiators.allow', "Path to the IET initiators allow file", 1),
    ('ietd_target_allow', 'IET target allow', 1, '/etc/iet/targets.allow', "Path to the IET targets allow file", 1),
    ('ietadm', 'ietadm bin', 1, '/usr/sbin/ietadm', "Path to the IET admin tool", 1),
    ('lvs', 'lvs bin', 1, '/sbin/lvs', "Path to the lvs binary", 2),
    ('vgs', 'vgs bin', 1, '/sbin/vgs', "Path to the vgs binary", 2),
    ('pvs', 'pvs bin', 1, '/sbin/pvs', "Path to the pvs binary", 2),
    ('lvcreate', 'lvcreate bin', 1, '/sbin/lvcreate', "Path to the lvcreate binary", 2),
    ('lvreduce', 'lvreduce bin', 1, '/sbin/lvreduce', "Path to the lvreduce binary", 2),
    ('lvextend', 'lvextend bin', 1, '/sbin/lvextend', "Path to the lvextend binary", 2),
    ('lvremove', 'lvremove bin', 1, '/sbin/lvremove', "Path to the lvremove binary", 2),
    ('mdstat', '/proc mdstat', 1, '/proc/mdstat', "Path to the mdstat file", 3),
    ('sudo', 'subo bin', 1, '/usr/bin/sudo', "Path to the sudo binary", 3),
    ('service', 'service bin', 1, '/usr/sbin/service', "Path to the service binary", 3),
    ('lsblk', 'lsblk bin', 1, '/bin/lsblk', "Path to the lsblk binary", 3),
    ('shutdown', 'shutdown bin', 1, '/sbin/shutdown', "Path to the shutdown binary", 3),
    ('idle', 'idle time', 0, 15, 'Time until the user is automatically logged out in minutes', 3);

INSERT INTO category (category) VALUES
    ('iet'),
    ('lvm'),
    ('misc');

INSERT INTO types (value, display_name) VALUES
  ('hostv4', 'IPv4 Host'),
  ('hostv6', 'IPv6 Host'),
  ('networkv4', 'IPv4 Network'),
  ('networkv6', 'IPv6 Network'),
  ('iqn', 'IQN'),
  ('all', 'ALL'),
  ('regex', 'Regex');

INSERT INTO objects (value, name, type_id) VALUES ('ALL', 'ALL', (SELECT type_id from types where value='all'));

INSERT INTO services (name, enabled) VALUES
  ('cron', 1),
  ('ssh', 1),
  ('drbd', 0),
  ('puppet', 0),
  ('iscsitarget', 1),
  ('nfs-kernel-server', 0),
  ('nagios-nrpe-server', 0),
  ('rsyslog', 1),
  ('apache2', 1 ),
  ('nullmailer',0 ),
  ('exim4', 0),
  ('corosync', 0),
  ('pacemaker', 0),
  ('apcupsd', 0),
  ('smbd', 0),
  ('nmbd', 0);