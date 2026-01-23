-- sql/init_db.sql
-- Updated schema: DBASSIGNMENT, USER and STUDENT tables per requested layout
CREATE DATABASE IF NOT EXISTS `DBASSIGNMENT`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;
USE `DBASSIGNMENT`;

-- USER table (added status column)
CREATE TABLE IF NOT EXISTS `USER` (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  role ENUM('admin', 'student') NOT NULL,
  status ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- STUDENT table
-- Contains the requested fields (uppercase names) plus the previous columns retained.
CREATE TABLE IF NOT EXISTS `STUDENT` (
  STUDENTID INT(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  FULLNAME VARCHAR(80) NOT NULL COLLATE utf8mb4_general_ci,
  ADDRESS1 VARCHAR(40) NOT NULL COLLATE utf8mb4_general_ci,
  ADDRESS2 VARCHAR(40) NOT NULL COLLATE utf8mb4_general_ci,
  POSTCODE VARCHAR(6) NOT NULL COLLATE utf8mb4_general_ci,
  CITY VARCHAR(30) NOT NULL COLLATE utf8mb4_general_ci,
  STATE VARCHAR(30) NOT NULL COLLATE utf8mb4_general_ci,
  GENDER VARCHAR(1) NOT NULL COLLATE utf8mb4_general_ci,
  RACE VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci,
  RELIGION VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci,
  CONTACTNO VARCHAR(12) NOT NULL COLLATE utf8mb4_general_ci,
  EMAIL VARCHAR(40) NOT NULL COLLATE utf8mb4_general_ci,

  -- Previous columns kept (lowercase names)
  matric_no VARCHAR(20) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  ic_no VARCHAR(20) NOT NULL UNIQUE,
  programme VARCHAR(100),
  faculty VARCHAR(100),
  semester INT,
  phone_no VARCHAR(20),
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;