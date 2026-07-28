-- Migration SQL for certifications table
-- Run this in phpMyAdmin or MySQL command line

USE nel_portfolio;

-- Add certificate_file column
ALTER TABLE certifications 
ADD COLUMN certificate_file VARCHAR(255) DEFAULT NULL AFTER issue_date;

-- Migrate existing credential_url data to certificate_file (if credential_url exists)
UPDATE certifications 
SET certificate_file = credential_url 
WHERE credential_url IS NOT NULL AND credential_url != '';

-- Remove old credential_url column (if it exists)
ALTER TABLE certifications 
DROP COLUMN IF EXISTS credential_url;
