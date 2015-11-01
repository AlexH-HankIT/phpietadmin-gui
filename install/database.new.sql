DROP TABLE IF EXISTS phpietadmin_config;
CREATE TABLE phpietadmin_config(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  option VARCHAR(50) NOT NULL,
  optioningui VARCHAR(50) NOT NULL,
  config_type_id BOOLEAN NOT NULL DEFAULT 1,
  value VARCHAR(50) NOT NULL,
  editable_via_gui BOOLEAN NOT NULL DEFAULT 1,
  description VARCHAR(200),
  config_category_id INT NOT NULL,
  field varchar(20)
);

DROP TABLE IF EXISTS phpietadmin_config_category;
CREATE TABLE phpietadmin_config_category(
  config_category_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  category varchar(20) NOT NULL
);

DROP TABLE IF EXISTS phpietadmin_config_type;
CREATE TABLE phpietadmin_config_type(
  config_type_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  type varchar(20) NOT NULL
);

DROP TABLE IF EXISTS phpietadmin_user;
CREATE TABLE phpietadmin_user(
  user_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username varchar(255) NOT NULL,
  password char(60) NOT NULL, /* for bcrypt hash */
  session_id INTEGER DEFAULT NULL
);

DROP TABLE IF EXISTS phpietadmin_object;
CREATE TABLE phpietadmin_object(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  type_id INTEGER NOT NULL,
  value varchar NOT NULL,
  name varchar NOT NULL
);

DROP TABLE IF EXISTS phpietadmin_object_type;
CREATE TABLE phpietadmin_object_type(
  type_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  display_name varchar(10) NOT NULL,
  value varchar(10) NOT NULL
);

DROP TABLE IF EXISTS phpietadmin_iet_user;
CREATE TABLE phpietadmin_iet_user(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL
);

DROP TABLE IF EXISTS phpietadmin_iet_setting;
CREATE TABLE phpietadmin_iet_setting(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  option varchar(50) NOT NULL,
  defaultvalue varchar(50) NOT NULL,
  type varchar(50) NOT NULL,
  state NUMERIC BOOLEAN NOT NULL,
  chars varchar(50),
  othervalue1 varchar(50) DEFAULT NULL
);

DROP TABLE IF EXISTS phpietadmin_service;
CREATE TABLE phpietadmin_service(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name varchar(50),
  enabled numeric boolean
);

DROP TABLE IF EXISTS phpietadmin_volume_group;
CREATE TABLE phpietadmin_volume_group(
  volume_group_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  volume_group_name varchar(255) NOT NULL
);

INSERT INTO phpietadmin_iet_setting (option, defaultvalue, type, state, chars) VALUES
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

INSERT INTO phpietadmin_iet_setting (option, defaultvalue, type, state, othervalue1) VALUES
  ('HeaderDigest', 'None', 'select', 1, 'CRC32C'),
  ('DataDigest', 'None', 'select', 1, 'CRC32C'),
  ('InitialR2T', 'Yes', 'select', 1, 'No'),
  ('ImmediateData', 'No', 'select', 1, 'Yes'),
  ('DataPDUInOrder', 'Yes', 'select', 0, 'No'),
  ('DataSequenceInOrder', 'Yes', 'select', 0, 'No');

INSERT INTO phpietadmin_config (option, optioningui, config_type_id, value, description, config_category_id, field) VALUES
    ('iqn', 'IQN', 1, 'iqn.2014-12.com.example.iscsi', "Names of the iscsi targets", 1, 'input'),
    ('proc_sessions', '/proc session', 2, '/proc/net/iet/session', "Path to the IET sessions file", 1, 'input'),
    ('proc_volumes', '/proc volume', 2, '/proc/net/iet/volume', "Path to the IET volumes file", 1, 'input'),
    ('ietd_config_file', 'IET config file', 2, '/etc/iet/ietd.conf', "Path to the IET config file", 1, 'input'),
    ('ietd_init_allow', 'IET initiator allow', 2, '/etc/iet/initiators.allow', "Path to the IET initiators allow file", 1, 'input'),
    ('ietd_target_allow', 'IET target allow', 2, '/etc/iet/targets.allow', "Path to the IET targets allow file", 1, 'input'),
    ('ietadm', 'ietadm bin', 5, '/usr/sbin/ietadm', "Path to the IET admin tool", 4, 'input'),
    ('lvs', 'lvs bin', 5, '/sbin/lvs', "Path to the lvs binary", 4, 'input'),
    ('vgs', 'vgs bin', 5, '/sbin/vgs', "Path to the vgs binary", 4, 'input'),
    ('pvs', 'pvs bin', 5, '/sbin/pvs', "Path to the pvs binary", 4, 'input'),
    ('lvcreate', 'lvcreate bin', 5, '/sbin/lvcreate', "Path to the lvcreate binary", 4, 'input'),
    ('lvrename', 'lvrename bin', 5, '/sbin/lvrename', "Path to the lvrename binary", 4, 'input'),
    ('lvreduce', 'lvreduce bin', 5, '/sbin/lvreduce', "Path to the lvreduce binary", 4, 'input'),
    ('lvextend', 'lvextend bin', 5, '/sbin/lvextend', "Path to the lvextend binary", 4, 'input'),
    ('lvremove', 'lvremove bin', 5, '/sbin/lvremove', "Path to the lvremove binary", 4, 'input'),
    ('lvconvert', 'lvconvert bin', 5, '/sbin/lvconvert', "Path to the lvconvert binary", 4, 'input'),
    ('mdstat', '/proc mdstat', 2, '/proc/mdstat', "Path to the mdstat file", 3, 'input'),
    ('backupDir', 'backupDir', 3, '/var/backups/phpietadmin', "Path to the phpietadmin backup folder", 6, 'input'),
    ('maxBackups', 'Max Backups', 3, '30', "How many backups should be stored?", 1, 'input'),
    ('sudo', 'subo bin', 4, '/usr/bin/sudo', "Path to the sudo binary", 4, 'input'),
    ('service', 'service bin', 5, '/usr/sbin/service', "Path to the service binary", 4, 'input'),
    ('lsblk', 'lsblk bin', 4, '/bin/lsblk', "Path to the lsblk binary", 4, 'input'),
    ('shutdown', 'shutdown bin', 5, '/sbin/shutdown', "Path to the shutdown binary", 4, 'input'),
    ('idle', 'idle time', 1, 15, 'Time until the user is automatically logged out in minutes, 0 means disabled', 3, 'input'),
    ('log_base', 'Log folder', 3, '/var/log/phpietadmin', 'Base dir for the phpietadmin log files', 5, 'input'),
    ('debug_log', 'Debug log filename', 2, 'debug.log', 'Filename of the debug log file', 5, 'input'),
    ('action_log', 'Action log filename', 2, 'action.log', 'Filename of the action log file', 5, 'input'),
    ('access_log', 'Access log filename', 2, 'access.log', 'Filename of the access log file', 5, 'input'),
    ('database_log', 'Database log filename', 2, 'database.log', 'Filename of the database log file', 5, 'input'),
    ('debug_log_enabled', 'Enable debug log', 6, 0, 'Log debug information', 5, 'input'),
    ('action_log_enabled', 'Enable action log', 6, 1, 'Log action information', 5, 'input'),
    ('access_log_enabled', 'Enable access log', 6, 1, 'Log access information', 5, 'input'),
    ('database_log_enabled', 'Enable database log', 6, 1, 'Log database information', 5, 'input');

INSERT INTO phpietadmin_config_category (category) VALUES
    ('iet'),
    ('lvm'),
    ('misc'),
    ('bin'),
    ('logging'),
    ('backup);

INSERT INTO phpietadmin_object_type (value, display_name) VALUES
  ('hostv4', 'IPv4 Host'),
  ('hostv6', 'IPv6 Host'),
  ('networkv4', 'IPv4 Network'),
  ('networkv6', 'IPv6 Network'),
  ('iqn', 'IQN'),
  ('all', 'ALL'),
  ('regex', 'Regex');

INSERT INTO phpietadmin_config_type(type) VALUES
  ('generic'),
  ('file'),
  ('folder'),
  ('bin'),
  ('subin'),
  ('bool');

INSERT INTO phpietadmin_object (value, name, type_id) VALUES ('ALL', 'ALL', (SELECT type_id from phpietadmin_object_type where value='all'));

INSERT INTO phpietadmin_service (name, enabled) VALUES
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