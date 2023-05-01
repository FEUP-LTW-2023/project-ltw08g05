/* Delete existing tables */
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Department;

/* Create the tables */
CREATE TABLE User(
    userId          INTEGER PRIMARY KEY,
    username        VARCHAR(255) UNIQUE NOT NULL,
    email           VARCHAR(255) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    name            TEXT NOT NULL,
    is_agent        BOOLEAN NOT NULL DEFAULT 'false',
    is_admin        BOOLEAN NOT NULL DEFAULT 'false',
    
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
    id_ticket       INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(userId),
    id_department   INTEGER REFERENCES Department(id_department),
    agent_assigned  INTEGER REFERENCES User(userId) ON UPDATE CASCADE ON DELETE SET NULL,
    title           VARCHAR(255) NOT NULL,
    content_text    TEXT NOT NULL,
    ticket_status   status DEFAULT 'Open' NOT NULL,
    creation_date   DATE DEFAULT (DATE('now')),

    CHECK(length(content_text) > 0 and length(content_text) <= 200)
);

CREATE TABLE Department (
    id_department   INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(userId),
    title           VARCHAR(255) NOT NULL,
    creation_date   DATE DEFAULT (DATE('now'))
);



/* Populate the database */

INSERT INTO User(userId, username, email, password, name, is_agent, is_admin)
VALUES
(1, 'john_doe', 'john.doe@example.com', 'password123', 'John Doe', 1, 0),
(2, 'jane_doe', 'jane.doe@example.com', 'password123', 'Jane Doe', 1, 0),
(3, 'bob_smith', 'bob.smith@example.com', 'password123', 'Bob Smith', 0, 1),
(4, 'alice_johnson', 'alice.johnson@example.com', 'password123', 'Alice Johnson', 0, 1),
(5, 'samantha_lake', 'samantha.lake@example.com', 'password123', 'Samantha Lake', 0, 0),
(6, 'joshua_lee', 'joshua.lee@example.com', 'password123', 'Joshua Lee', 0, 0),
(7, 'mary_smith', 'mary.smith@example.com', 'password123', 'Mary Smith', 1, 0),
(8, 'lucy_kim', 'lucy.kim@example.com', 'password123', 'Lucy Kim', 1, 0),
(9, 'kevin_hernandez', 'kevin.hernandez@example.com', 'password123', 'Kevin Hernandez', 1, 0),
(10, 'emily_park', 'emily.park@example.com', 'password123', 'Emily Park', 1, 0);

INSERT INTO Agent(id_agent)
VALUES
(1),
(2),
(7),
(8),
(9),
(10);

INSERT INTO Admin(id_admin)
VALUES
(3),
(4);

INSERT INTO Ticket(id_ticket, id_user, id_department, agent_assigned, title, content_text, ticket_status)
VALUES
(1, 1, 1, 1, 'Issue with login', 'I am unable to log in to my account.', 'Open'),
(2, 2, 2, 1, 'Forgot password', 'I forgot my password and need help resetting it.', 'Open'),
(3, 1, 3, NULL, 'Payment issue', 'I was charged twice for my subscription.', 'Assigned'),
(4, 5, 4, 2, 'Bug in the app', 'There is a bug in the app that crashes it.', 'Open'),
(5, 6, 5, NULL, 'Feature request', 'It would be great to have a dark mode option.', 'Open'),
(6, 3, 6, NULL, 'Complaint', 'I am unhappy with the service and would like to cancel my subscription.', 'Open'),
(7, 7, 7, 1, 'Billing question', 'I have a question about my billing statement.', 'Close'),
(8, 8, 8, NULL, 'Account issue', 'I am unable to update my account information.', 'Open'),
(9, 9, 9, 2, 'Feedback', 'I really like the app and have some suggestions for improvement.', 'Open'),
(10, 10, 10, NULL, 'General inquiry', 'I have a question about the app.', 'Open');

INSERT INTO Department (id_department, title) 
VALUES 
(1, 'Human Resources'),
(2, 'Marketing'),
(3, 'Sales'),
(4, 'Engineering'),
(5, 'Finance'),
(6, 'Customer Service'),
(7, 'IT'),
(8, 'Research and Development'),
(9, 'Legal'),
(10, 'Operations');


/* Enable foreign key constraint support */
PRAGMA foreign_keys = ON;



INSERT INTO User (username, email, password, name, is_admin)
VALUES ('john_doe', 'john_doe@example.com', 'password123', 'John Doe', 'false');
