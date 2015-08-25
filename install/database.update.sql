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

DROP TABLE IF EXISTS config_type;
CREATE TABLE config_type(
  config_type_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  type varchar(20) NOT NULL
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

DROP TABLE CONFIG;

INSERT INTO category (category) VALUES ('bin');
INSERT INTO category (category) VALUES ('logging');

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

  // ToDo: Add config data