-- matches table
CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    match_number INT,
    match_type VARCHAR(20),
    alliance_color VARCHAR(10),

    predicted_winner VARCHAR(20),
    actual_winner VARCHAR(20),
    ranking_points INT,
    opponents TEXT,

    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- teams table (linked to match)
CREATE TABLE match_teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT,

    team_number INT,
    path VARCHAR(10),
    disabled VARCHAR(10),

    capabilities TEXT,

    FOREIGN KEY (match_id) REFERENCES matches(match_id) ON DELETE CASCADE
);