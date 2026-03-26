-- Add profile_picture columns to existing tables
-- Run this script in phpMyAdmin to update existing database

-- Add profile_picture column to students table
ALTER TABLE students ADD COLUMN profile_picture TEXT AFTER address;

-- Add profile_picture column to faculty table  
ALTER TABLE faculty ADD COLUMN profile_picture TEXT AFTER address;

-- Update existing records with default profile picture (optional)
UPDATE students SET profile_picture = 'https://cdn1.iconfinder.com/data/icons/language-studies-thick-outline/33/language-10-1024.png' WHERE profile_picture IS NULL;
UPDATE faculty SET profile_picture = 'https://cdn1.iconfinder.com/data/icons/language-studies-thick-outline/33/language-10-1024.png' WHERE profile_picture IS NULL;

-- Verify the changes
SELECT 'Students table updated successfully' as status;
SELECT 'Faculty table updated successfully' as status;
