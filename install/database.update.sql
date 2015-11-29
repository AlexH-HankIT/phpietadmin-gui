INSERT INTO phpietadmin_config_category(category) VALUES('release');

INSERT INTO phpietadmin_config (option, optioningui, config_type_id, value, description, config_category_id, field) VALUES
('releaseCheck', 'Release check', 1, 'stable', "Names of the iscsi targets", (SELECT id from phpietadmin_config_type where type='release'), 'select');