-- Student Information System Database Initialization
-- Database: DBASSIGNMENT
-- Tables: USER, STUDENT
-- Character Set: utf8mb4, Engine: InnoDB

-- Drop database if exists and create new
DROP DATABASE IF EXISTS DBASSIGNMENT;
CREATE DATABASE DBASSIGNMENT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DBASSIGNMENT;

-- USER table for authentication
-- Stores admin and student user accounts
CREATE TABLE USER (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- STUDENT table for student records
-- Stores comprehensive student information
CREATE TABLE STUDENT (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    matric_no VARCHAR(20) NOT NULL UNIQUE,
    ic_no VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    gender ENUM('Male', 'Female') NOT NULL,
    program VARCHAR(100),
    year_of_study INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_matric_no (matric_no),
    INDEX idx_ic_no (ic_no),
    INDEX idx_full_name (full_name),
    INDEX idx_email (email)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
