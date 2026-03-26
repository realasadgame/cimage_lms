-- Fix Database Script for CIMAGE LMS
-- Run this script in phpMyAdmin to add missing address columns

-- Add address column to students table if not exists
ALTER TABLE students ADD COLUMN address TEXT AFTER phone;

-- Add address column to faculty table if not exists  
ALTER TABLE faculty ADD COLUMN address TEXT AFTER phone;

-- Update sample data with addresses (optional)
UPDATE students SET address = '4th floor Umang Enclave, Vikas, Fair Field Colony, Patna 800011' WHERE student_id = '11942' AND address IS NULL;
UPDATE faculty SET address = '123 College Road, Patna 800001' WHERE faculty_id = '444' AND address IS NULL;

-- Show updated table structures
DESCRIBE students;
DESCRIBE faculty;
