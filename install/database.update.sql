INSERT INTO phpietadmin_config_category(category) VALUES('release');

INSERT INTO phpietadmin_config (option, optioningui, config_type_id, value, description, config_category_id, field) VALUES
('releaseCheck', 'Release check', 1, 'stable', "Release channel", (SELECT config_category_id from phpietadmin_config_category where category='release'), 'select'),
('betaReleaseUrl', 'Beta release url', 1, 'https://raw.githubusercontent.com/HankIT/phpietadmin-doc/version/beta.json', '', (SELECT config_category_id from phpietadmin_config_category where category='release'), 'input'),
('stableReleaseUrl', 'Stable release url', 1, 'https://raw.githubusercontent.com/HankIT/phpietadmin-doc/version/stable.json', '', (SELECT config_category_id from phpietadmin_config_category where category='release'), 'input');