-- Create hero tech stack table for uploadable tech icons
-- Run this SQL to add tech stack functionality to hero section

CREATE TABLE IF NOT EXISTS hero_tech_stack (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tech_name VARCHAR(50) NOT NULL,
    icon_path VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create uploads directory structure (create manually)
-- /uploads/tech-stack/
