-- Sample data for robotics team website database
-- Author: Ali Abdullayev



INSERT INTO roles (role_name) VALUES
('Lead Teacher'),
('Team Captain'),
('Lead Technician'),
('Automation Lead'),
('Drive Coach');

INSERT INTO members (first_name, last_name, display_name, role_id, bio, is_teacher, email)
VALUES
('Mr.', 'Steel', 'Mr. Steel', 1, 'Lead teacher supporting the robotics team.', 1, 'steel@example.com'),
('Crystal', '', 'Crystal', 2, 'Team captain who helps organize and lead the team.', 0, 'crystal@example.com'),
('Gabe', '', 'Gabe', 3, 'Lead technician responsible for technical development.', 0, 'gabe@example.com'),
('Lemmy', '', 'Lemmy', 4, 'Automation lead focused on programming and control systems.', 0, 'lemmy@example.com'),
('Fernando', '', 'Fernando', 5, 'Drive coach helping guide robot performance.', 0, 'fernando@example.com'),
('Aaron', '', 'Aaron', 5, 'Drive coach supporting strategy and match execution.', 0, 'aaron@example.com');