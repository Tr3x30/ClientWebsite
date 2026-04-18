-- Database schema for robotics team website
-- Updated user approval + admin + member profile system

DROP TABLE IF EXISTS match_teams;
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS pending_users;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS robots;
DROP TABLE IF EXISTS sponsors;

CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(100) NOT NULL
);

CREATE TABLE members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100),
    display_name VARCHAR(150) NOT NULL,
    role_id INT NOT NULL,
    bio TEXT,
    image_path VARCHAR(255),
    is_teacher TINYINT(1) DEFAULT 0,
    email VARCHAR(255),
    active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    account_type VARCHAR(50) DEFAULT 'member',
    last_login DATETIME,
    active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

CREATE TABLE robots (
    robot_id INT AUTO_INCREMENT PRIMARY KEY,
    robot_name VARCHAR(100) NOT NULL,
    season_year INT,
    description TEXT,
    image_path VARCHAR(255),
    status VARCHAR(50)
);

CREATE TABLE sponsors (
    sponsor_id INT AUTO_INCREMENT PRIMARY KEY,
    sponsor_name VARCHAR(150) NOT NULL,
    website_url VARCHAR(255),
    logo_path VARCHAR(255),
    sponsor_level VARCHAR(50),
    description TEXT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) DEFAULT 'Team Member',
    description TEXT,
    profile_picture VARCHAR(255) DEFAULT 'images/empty_icon.webp',
    email VARCHAR(255),
    is_admin TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 1,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pending_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) DEFAULT 'Pending Member',
    description TEXT,
    profile_picture VARCHAR(255) DEFAULT 'images/empty_icon.webp',
    email VARCHAR(255),
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    match_number INT NOT NULL,
    match_type VARCHAR(50) NOT NULL,
    alliance_color VARCHAR(20) NOT NULL,
    predicted_winner VARCHAR(20) NOT NULL,
    actual_winner VARCHAR(20) NOT NULL,
    ranking_points INT NULL,
    opponents VARCHAR(255),
    details TEXT
);

CREATE TABLE match_teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    team_number INT NOT NULL,
    path VARCHAR(20),
    disabled VARCHAR(10),
    capabilities JSON,
    FOREIGN KEY (match_id) REFERENCES matches(match_id) ON DELETE CASCADE
);