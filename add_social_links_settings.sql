-- Add social media link settings to the settings table
-- Run this SQL to add social link customization for the hero section

INSERT INTO settings (setting_key, setting_value) VALUES
('hero_social_github', ''),
('hero_social_linkedin', ''),
('hero_social_twitter', '')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
