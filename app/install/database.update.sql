CREATE TABLE phpietadmin(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  option varchar(50) NOT NULL,
  value varchar(50) NOT NULL
);

INSERT INTO phpietadmin (option, value) VALUES ('version', 'v0.4');