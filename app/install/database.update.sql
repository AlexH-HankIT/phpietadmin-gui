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
  ('idle', 'idle time', 0, 15, 'Time until the user is automatically logged out in minutes', 3);