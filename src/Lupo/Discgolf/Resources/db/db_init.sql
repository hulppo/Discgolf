/**
 * Lupo Discgolf software database table structure.
 * Initially created for PostgreSQL database.
 */

/**
* Table for containing discgolf course information.
* We probably need a table with alternative names for the same course
* for easy matching from the emails.
*/
CREATE TABLE course (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    location TEXT
);

/**
* The following table is used to create synonyme names for courses.
*/
CREATE TABLE course_name (
    id SERIAL PRIMARY KEY,
    course_id INTEGER REFERENCES course,
    alt_name TEXT NOT NULL
);

/**
* Discgolf basket/hole on one given course.
* Number is the hole's number for the course.
*/
CREATE TABLE hole (
    id SERIAL PRIMARY KEY,
    course_id INTEGER REFERENCES course,
    number INTEGER,
    par INTEGER,
    length INTEGER,
    description TEXT
);

/**
 * User of the discgolf application.
 */
CREATE TABLE dg_user (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL,
    name TEXT NOT NULL
);


/**
 * One discgolf round on one course.
 */
CREATE TABLE round (
    id SERIAL PRIMARY KEY,
    course_id INTEGER REFERENCES course,
    timestamp TIMESTAMP NOT NULL,
    reporter_id INTEGER REFERENCES dg_user,
    description TEXT,
    hash TEXT UNIQUE NOT NULL
);

/**
 * A known discgolf player.
 */
CREATE TABLE player (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

/**
 * Possible extra names for the same player.
 * Required due to people possibly having different names
 * in for example other peoples phones.
 */
CREATE TABLE player_name (
    id SERIAL PRIMARY KEY,
    player_id INTEGER REFERENCES player,
    alt_name TEXT NOT NULL
);

/**
 * A player has participied in a given round.
 * Might be helpful when we have players with only
 * some valid results or none on a given round.
 * Also speeds up the search of player round data?
 */
CREATE TABLE round_participant (
    id SERIAL PRIMARY KEY,
    round_id INTEGER REFERENCES round,
    player_id INTEGER REFERENCES player,
    UNIQUE(round_id, player_id)
);

/**
 * The amount of how many throws one player needed
 * for one specific hole on one specific round.
 */
CREATE TABLE result (
    id SERIAL PRIMARY KEY,
    round_id INTEGER REFERENCES round,
    hole_id INTEGER REFERENCES hole,
    player_id INTEGER REFERENCES player,
    throws INTEGER,
    UNIQUE(round_id, hole_id, player_id)
);

/**
 * Adding of OB and putt count to results.
 */
ALTER TABLE result ADD COLUMN putts INTEGER DEFAULT NULL;
ALTER TABLE result ADD COLUMN out_of_bounds INTEGER DEFAULT NULL;

