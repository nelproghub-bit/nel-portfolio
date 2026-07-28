-- Add projects section header customization settings
-- Run this SQL to add projects header customization

INSERT INTO settings (setting_key, setting_value) VALUES
('projects_badge_text', 'Portfolio Showcase'),
('projects_title', 'Selected Works'),
('projects_subtitle', 'A curated collection of my most impactful projects, showcasing innovation, technical expertise, and creative problem-solving.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);