-- Add super_admin role to users table

ALTER TABLE users 
MODIFY COLUMN role ENUM('super_admin', 'admin', 'donor') DEFAULT 'donor';

-- Make default admin a super admin
UPDATE users 
SET role = 'super_admin' 
WHERE email = 'admin@mrsmkp.edu.my';



