-- Active: 1772512305651@@127.0.0.1@3306@mysql
-- Bobony Family Staff Login System - Database Setup
-- Run this SQL to set up the database

-- Create Database
CREATE DATABASE IF NOT EXISTS bobony_db;
USE bobony_db;

-- Create Staff Table
CREATE TABLE IF NOT EXISTS staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'owner', 'staff') NOT NULL DEFAULT 'staff',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    email VARCHAR(100) NULL,
    reset_token_hash VARCHAR(64) NULL,
    reset_token_expires DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert staff login account (plain text password used: admin123)
INSERT INTO staff (username, password, role, status, email) VALUES 
('admin', 'admin123', 'owner', 'active', 'admin@bobony.com');

-- Create Activity Log Table (optional)
CREATE TABLE IF NOT EXISTS activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Logs Table
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255) NOT NULL,
    details TEXT NOT NULL,
    user_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('success', 'failed', 'warning') NOT NULL DEFAULT 'success',
    FOREIGN KEY (user_id) REFERENCES staff(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Bans Table
CREATE TABLE IF NOT EXISTS bans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    discord_username VARCHAR(100) NOT NULL,
    discord_id VARCHAR(50) NOT NULL UNIQUE,
    ban_reason VARCHAR(255) NOT NULL,
    ban_duration INT NOT NULL COMMENT 'Duration in hours (0 for permanent)',
    ban_count INT DEFAULT 1,
    is_permanent BOOLEAN DEFAULT FALSE,
    is_blacklisted BOOLEAN DEFAULT FALSE,
    banned_by INT NOT NULL,
    banned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unban_date DATETIME NULL,
    status ENUM('active', 'appealed', 'unbanned') DEFAULT 'active',
    notes TEXT NULL,
    FOREIGN KEY (banned_by) REFERENCES staff(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Streamers Table
CREATE TABLE IF NOT EXISTS streamers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    -- `image` stores the URL/path to the streamer's profile image
    image VARCHAR(255) NULL,
    link1 VARCHAR(255) NULL,
    link2 VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    details TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reglements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL DEFAULT 'General Rules',
    rule_text TEXT NOT NULL,
    ban_time VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO reglements (category, rule_text, ban_time) 
VALUES ('New Rules', 'Placeholder rule for New Rules category.', '');

CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL DEFAULT 'Admin RP',
    image VARCHAR(255) NULL,
    link1 VARCHAR(255) NULL,
    link2 VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
-- If you already created the table before this update, run:
-- ALTER TABLE streamers CHANGE profile image VARCHAR(255) NULL;
-- (and optionally DROP COLUMN profile if you don't need it)

/* 
HOW TO USE:
1. Install XAMPP or any local PHP/MySQL server
2. Create a new database or run the SQL above in phpMyAdmin
3. Update the config.php file with your database credentials:
   - DB_HOST: usually 'localhost'
   - DB_USER: usually 'root'
   - DB_PASSWORD: your MySQL password (empty by default)
   - DB_NAME: 'bobony_db'

4. Login with:
   - Username: admin
   - Password: admin123

5. Access the login page at: http://localhost/bobony/login.php
*/
