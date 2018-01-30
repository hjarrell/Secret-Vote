-- Just create the database
CREATE DATABASE IF NOT EXISTS voting;

-- Set voting to the current database we are useing
USE voting;

CREATE TABLE IF NOT EXISTS current_poll ( -- Table that holds the current thing we are voting on
        id enum('1') NOT NULL, -- This forces the column to only be 1
        name VARCHAR(150) NOT NULL, -- The name of the person we are voting on
        PRIMARY KEY (id) -- Force id to be unique so since it can only be 1 the table can only have 1 row
);

CREATE TABLE IF NOT EXISTS votes ( -- Table to hold the votes people cast
    name VARCHAR(150) NOT NULL, -- Name of the thing we are voting on
    vote enum('YES', 'NO', 'ABSTAIN') NOT NULL -- The only possible votes
);

GRANT SELECT,INSERT,DELETE ON voting.* TO 'secretVote'@'%' IDENTIFIED BY 'test';

flush privileges;
