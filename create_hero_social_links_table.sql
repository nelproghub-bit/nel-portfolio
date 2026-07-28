-- Create hero social links table for dynamic social media management
-- Run this SQL to add CRUD functionality for social links

CREATE TABLE IF NOT EXISTS hero_social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform_name VARCHAR(50) NOT NULL,
    platform_icon VARCHAR(100) NOT NULL,
    profile_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
