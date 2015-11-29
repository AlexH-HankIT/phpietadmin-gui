INSERT INTO phpietadmin_config_category(category) VALUES('release');

INSERT INTO phpietadmin_config (option, optioningui, config_type_id, value, description, config_category_id, field) VALUES
('releaseCheck', 'Release check', 1, 'stable', "Release channel", (SELECT config_category_id from phpietadmin_config_category where category='release'), 'select');