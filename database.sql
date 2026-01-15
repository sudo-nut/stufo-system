-- Student Information System Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS student_system;
USE student_system;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matric_no VARCHAR(50) UNIQUE NOT NULL,
    ic_no VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    program VARCHAR(100),
    year_of_study INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_matric (matric_no),
    INDEX idx_ic (ic_no),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample student data
INSERT INTO students (matric_no, ic_no, name, email, phone, address, program, year_of_study) VALUES
('M2023001', '990101-01-1234', 'Ahmad bin Abdullah', 'ahmad@student.edu', '012-3456789', '123 Jalan Mawar, Kuala Lumpur', 'Computer Science', 2),
('M2023002', '990202-02-2345', 'Siti Nurhaliza', 'siti@student.edu', '012-4567890', '456 Jalan Orkid, Petaling Jaya', 'Information Technology', 1),
('M2023003', '990303-03-3456', 'Lee Wei Ming', 'lee@student.edu', '012-5678901', '789 Jalan Cempaka, Shah Alam', 'Software Engineering', 3),
('M2023004', '990404-04-4567', 'Fatimah binti Hassan', 'fatimah@student.edu', '012-6789012', '321 Jalan Melati, Subang Jaya', 'Computer Science', 2),
('M2023005', '990505-05-5678', 'Raj Kumar', 'raj@student.edu', '012-7890123', '654 Jalan Dahlia, Klang', 'Information Technology', 1),
('M2023006', '990606-06-6789', 'Nurul Ain', 'nurul@student.edu', '012-8901234', '987 Jalan Ros, Kajang', 'Software Engineering', 4),
('M2023007', '990707-07-7890', 'Wong Kar Wai', 'wong@student.edu', '012-9012345', '147 Jalan Lily, Ampang', 'Computer Science', 1),
('M2023008', '990808-08-8901', 'Aminah binti Ibrahim', 'aminah@student.edu', '012-0123456', '258 Jalan Tulip, Cheras', 'Information Technology', 2),
('M2023009', '990909-09-9012', 'Chong Wei Lun', 'chong@student.edu', '012-1234567', '369 Jalan Sunflower, Puchong', 'Software Engineering', 3),
('M2023010', '991010-10-0123', 'Zainab binti Ali', 'zainab@student.edu', '012-2345678', '741 Jalan Jasmine, Seremban', 'Computer Science', 1);
