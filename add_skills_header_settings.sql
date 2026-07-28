-- Add skills section header customization settings
-- Run this SQL to add skills header customization

INSERT INTO settings (setting_key, setting_value) VALUES
('skills_badge_text', 'Technical Proficiency'),
('skills_title', 'Technical Arsenal'),
('skills_subtitle', 'A comprehensive toolkit of cutting-edge technologies and frameworks I leverage to build exceptional digital experiences.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);