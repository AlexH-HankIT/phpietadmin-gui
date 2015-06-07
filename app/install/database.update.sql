DROP TABLE IF EXISTS ietusers;
CREATE TABLE ietusers(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL
);

DELETE FROM config where option='ietd_init_deny';