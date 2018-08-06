-- Just create the database
CREATE DATABASE IF NOT EXISTS voting;

-- Set voting to the current database we are useing
USE voting;

-- Holds the basic poll information
CREATE TABLE IF NOT EXISTS polls (
    id INT AUTO_INCREMENT,
    title VARCHAR(500) NOT NULL, -- Title of the poll
    PRIMARY KEY (id)
);

-- Holds the id of the current poll being voted on
CREATE TABLE IF NOT EXISTS current_poll (
    id ENUM('1') NOT NULL, -- This forces the id to only be 1
    poll_id INT NOT NULL,  -- ID of the poll being voted on
    voting_type ENUM('once', 'many', 'password', 'otp'), -- Type of voting such as voting only once...
    pword VARCHAR(150), -- Optional password for password protected polls
    PRIMARY KEY (id),      -- This forces there to either be 1 or 0 rows since primary keys are unique
    FOREIGN KEY (poll_id) REFERENCES polls(id)
);

-- Holds the actual poll options
CREATE TABLE IF NOT EXISTS poll_options (
    id INT AUTO_INCREMENT,
    poll_id INT NOT NULL,
    option_text VARCHAR(250) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (poll_id) REFERENCES polls(id)
);

-- Holds the votes people cast
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT,
    poll_id INT NOT NULL, -- Poll that was voted on
    option_id INT NOT NULL, -- Option that was chosen
    PRIMARY KEY (id),
    FOREIGN KEY (poll_id) REFERENCES polls(id),
    FOREIGN KEY (option_id) REFERENCES poll_options(id)
);

CREATE USER 'secretVote'@'%' IDENTIFIED WITH mysql_native_password BY 'test';

GRANT SELECT,INSERT,DELETE ON voting.* TO 'secretVote'@'%';

FLUSH PRIVILEGES;
