-- Database schema for robotics team website
-- Author: Ali Abdullayev


CREATE TABLE roles (
    role_id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_name TEXT NOT NULL
);

CREATE TABLE members (
    member_id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT,
    display_name TEXT NOT NULL,
    role_id INTEGER NOT NULL,
    bio TEXT,
    image_path TEXT,
    is_teacher INTEGER DEFAULT 0,
    email TEXT,
    active INTEGER DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE accounts (
    account_id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL UNIQUE,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    account_type TEXT DEFAULT 'member',
    last_login TEXT,
    active INTEGER DEFAULT 1,
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

CREATE TABLE robots (
    robot_id INTEGER PRIMARY KEY AUTOINCREMENT,
    robot_name TEXT NOT NULL,
    season_year INTEGER,
    description TEXT,
    image_path TEXT,
    status TEXT
);

CREATE TABLE sponsors (
    sponsor_id INTEGER PRIMARY KEY AUTOINCREMENT,
    sponsor_name TEXT NOT NULL,
    website_url TEXT,
    logo_path TEXT,
    sponsor_level TEXT,
    description TEXT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);