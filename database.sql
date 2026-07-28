-- Create the database
CREATE DATABASE IF NOT EXISTS nel_portfolio;
USE nel_portfolio;

-- Users table for admin auth
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    live_link VARCHAR(255),
    github_link VARCHAR(255),
    tech_stack TEXT, -- Comma-separated list of technologies
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Skills table
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    proficiency_level VARCHAR(20) NOT NULL, -- e.g., Beginner, Intermediate, Advanced, Expert
    category VARCHAR(50), -- e.g., Frontend, Backend, Tools
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Certifications table
CREATE TABLE IF NOT EXISTS certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    issuing_organization VARCHAR(100),
    issue_date DATE,
    certificate_file VARCHAR(255), -- Changed from credential_url to certificate_file
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings table for global config like resume summary
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT
);

-- Hero Tech Stack table
CREATE TABLE IF NOT EXISTS hero_tech_stack (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tech_name VARCHAR(50) NOT NULL,
    icon_path VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hero Social Links table
CREATE TABLE IF NOT EXISTS hero_social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform_name VARCHAR(50) NOT NULL,
    platform_icon VARCHAR(100) NOT NULL,
    profile_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('resume_summary', 'I am a passionate developer...'),
('resume_pdf_url', ''),
('about_years_experience', '5+'),
('about_total_projects', '50+'),
('about_total_clients', '20+'),
('about_email', 'contact@nel.dev'),
('about_location', 'Remote / Worldwide'),
('about_core_expertise', 'Full Stack Development,UI/UX Design,Cloud Architecture,DevOps'),
('about_profile_photo', ''),
('hero_badge_text', 'Available for Work'),
('hero_title_line1', 'Creative'),
('hero_title_line2', 'Developer'),
('hero_title_font_family', 'Outfit'),
('hero_title_font_size', '72'),
('hero_title_font_weight', '900'),
('hero_subtitle', 'Crafting <span class="text-white font-medium">digital experiences</span> that merge cutting-edge technology with <span class="text-white font-medium">premium design aesthetics</span>.'),
('hero_primary_btn_text', 'Discover My Work'),
('hero_primary_btn_link', '#about'),
('hero_secondary_btn_text', 'View Projects'),
('hero_secondary_btn_link', '#projects'),
('hero_social_github', ''),
('hero_social_linkedin', ''),
('hero_social_twitter', ''),
('skills_badge_text', 'Technical Proficiency'),
('skills_title', 'Technical Arsenal'),
('skills_subtitle', 'A comprehensive toolkit of cutting-edge technologies and frameworks I leverage to build exceptional digital experiences.'),
('projects_badge_text', 'Portfolio Showcase'),
('projects_title', 'Selected Works'),
('projects_subtitle', 'A curated collection of my most impactful projects, showcasing innovation, technical expertise, and creative problem-solving.'),
('projects_live_btn_text', 'Live Demo'),
('projects_code_btn_text', 'Source Code'),
('projects_status_live_text', 'Live'),
('projects_status_dev_text', 'In Development'),
('projects_completed_text', 'Completed');
