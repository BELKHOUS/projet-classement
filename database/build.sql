DROP TABLE IF EXISTS ranking;
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS teams;
DROP TABLE IF EXISTS users;

CREATE TABLE teams(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE matches(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    team0 INTEGER,
    team1 INTEGER,
    score0 INTEGER NOT NULL,
    score1 INTEGER NOT NULL,
    date DATETIME NOT NULL,
    FOREIGN KEY (team0) REFERENCES teams(id),
    FOREIGN KEY (team1) REFERENCES teams(id),
    UNIQUE (team0, team1),
    CHECK (team0 != team1)
);

CREATE TABLE ranking(
    team_id INTEGER PRIMARY KEY,
    rank INTEGER NOT NULL,
    match_played_count INTEGER NOT NULL,
    won_match_count INTEGER NOT NULL,
    lost_match_count INTEGER NOT NULL,
    draw_match_count INTEGER NOT NULL,
    goal_for_count INTEGER NOT NULL,
    goal_against_count INTEGER NOT NULL,
    goal_difference INTEGER NOT NULL,
    points INTEGER NOT NULL,
    FOREIGN KEY (team_id) REFERENCES teams(id),
    UNIQUE (rank)
);
CREATE TABLE users(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(128) NOT NULL,
    password_hash VARCHAR(128) NOT NULL,
    UNIQUE(email)
)