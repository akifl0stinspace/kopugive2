-- Add test admin account (non-super admin)
-- Password: admin123

INSERT INTO users (full_name, email, phone, password_hash, role, is_active) 
VALUES ('Test Admin', 'testadmin@mrsmkp.edu.my', '0123456780', '$2y$10$YJGwM7RCLDSqYC0LvJqyJuVG9QVvzHPFQ6dWzKxLm8HvmVGFZGONe', 'admin', 1)
ON DUPLICATE KEY UPDATE role = 'admin';









