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

INSERT INTO config (option, optioningui, ispath, value, description, category) VALUES
  ('idle', 'idle time', 0, 15, 'Time until the user is automatically logged out in minutes', 3),
  ('shutdown', 'shutdown bin', 1, '/sbin/shutdown', "Path to the shutdown binary", 3);

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