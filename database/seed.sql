-- Seed data

INSERT INTO roles (role_name) VALUES 
('Lead Teacher'),
('Team Captain'),
('Lead Technician'),
('Automation Lead'),
('Drive Coach');

INSERT INTO members (first_name, last_name, display_name, role_id, bio, image_path, is_teacher, email)
VALUES
('Mr.', 'Steel', 'Mr. Steel', 1, 'Lead teacher supporting the robotics team.', 'images/1759325627715.jpg', 1, 'steel@example.com'),
('Crystal', '', 'Crystal', 2, 'Team captain who helps organize and lead the team.', 'images/empty_icon.webp', 0, 'crystal@example.com'),
('Gabe', '', 'Gabe', 3, 'Lead technician responsible for technical development.', 'images/empty_icon.webp', 0, 'gabe@example.com'),
('Lemmy', '', 'Lemmy', 4, 'Automation lead focused on programming and control systems.', 'images/empty_icon.webp', 0, 'lemmy@example.com'),
('Fernando', '', 'Fernando', 5, 'Drive coach helping guide robot performance.', 'images/empty_icon.webp', 0, 'fernando@example.com'),
('Aaron', '', 'Aaron', 5, 'Drive coach supporting strategy and match execution.', 'images/empty_icon.webp', 0, 'aaron@example.com');

INSERT INTO users (
    username,
    password_hash,
    display_name,
    title,
    description,
    profile_picture,
    email,
    is_admin,
    is_approved,
    active
) VALUES
(
    'mrsteel',
    'PASTE_REAL_HASH_HERE',
    'Mr. Steel',
    'Lead Teacher',
    'Supports the robotics team and provides technical guidance.',
    'images/1759325627715.jpg',
    'steel@example.com',
    1,
    1,
    1
),
(
    'crystal',
    'PASTE_REAL_HASH_HERE',
    'Crystal',
    'Team Captain',
    'Leads the team and organizes development and strategy.',
    'images/empty_icon.webp',
    'crystal@example.com',
    0,
    1,
    1
),
(
    'gabe',
    'PASTE_REAL_HASH_HERE',
    'Gabe',
    'Lead Technician',
    'Responsible for technical development and hardware systems.',
    'images/empty_icon.webp',
    'gabe@example.com',
    0,
    1,
    1
),
(
    'lemmy',
    'PASTE_REAL_HASH_HERE',
    'Lemmy',
    'Automation Lead',
    'Focuses on programming and robot control systems.',
    'images/empty_icon.webp',
    'lemmy@example.com',
    0,
    1,
    1
),
(
    'fernando',
    'PASTE_REAL_HASH_HERE',
    'Fernando',
    'Drive Coach',
    'Helps guide robot driving strategy during competitions.',
    'images/empty_icon.webp',
    'fernando@example.com',
    0,
    1,
    1
),
(
    'aaron',
    'PASTE_REAL_HASH_HERE',
    'Aaron',
    'Drive Coach',
    'Supports match strategy and robot performance.',
    'images/empty_icon.webp',
    'aaron@example.com',
    0,
    1,
    1
);