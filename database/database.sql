/* Delete existing tables */
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Department;

CREATE TABLE User(
    userId INTEGER PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name TEXT NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT 'false',

    CHECK(length(username) > 0 and length(username) <= 50),
    CHECK(email LIKE '%_@_%._%'),
    CHECK(length(password) >= 6)
);

CREATE TABLE Agent(
    id_agent INTEGER PRIMARY KEY REFERENCES User(userId)
);

CREATE TABLE Admin (
    id_admin INTEGER PRIMARY KEY REFERENCES User(userId)
);

CREATE TABLE Ticket (
    id_ticket INTEGER PRIMARY KEY,
    id_user INTEGER REFERENCES User(userId),
    agent_assigned INTEGER REFERENCES User(userId) ON UPDATE CASCADE ON DELETE SET NULL,
    title VARCHAR(255) NOT NULL,
    content_text TEXT NOT NULL,
    ticket_status status DEFAULT 'Open' NOT NULL,
    creation_date DATE DEFAULT (DATE('now')),

    CHECK(length(content_text) > 0 and length(content_text) <= 200)
);

CREATE TABLE Department (
    id_department INTEGER PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    creation_date DATE DEFAULT (DATE('now'))
);

/* Enable foreign key constraint support */
PRAGMA foreign_keys = ON;