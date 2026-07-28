-- Add hero font styling settings to the settings table
-- Run this SQL to add font customization options for the hero section

INSERT INTO settings (setting_key, setting_value) VALUES
('hero_title_font_family', 'Outfit'),
('hero_title_font_size', '72'),
('hero_title_font_weight', '900')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
