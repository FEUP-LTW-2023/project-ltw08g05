/* Delete existing tables */
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Department;
DROP TABLE IF EXISTS FAQ;

/* Create the tables */
CREATE TABLE User(
    id          INTEGER PRIMARY KEY,
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
    id INTEGER PRIMARY KEY REFERENCES User(userId)
);

CREATE TABLE Admin (
    id INTEGER PRIMARY KEY REFERENCES User(userId)
);

CREATE TABLE Ticket (
    id       INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(userId),
    id_department   INTEGER REFERENCES Department(id_department),
    agent_assigned  INTEGER REFERENCES User(userId) ON UPDATE CASCADE ON DELETE SET NULL,
    title           VARCHAR(255) NOT NULL,
    content_text    TEXT NOT NULL,
    ticket_status   status DEFAULT 'Open' NOT NULL,
    creation_date   DATE DEFAULT (DATE('now')),
    update_date     DATE DEFAULT null,

    CHECK(length(content_text) > 0 and length(content_text) <= 200)
);

CREATE TABLE Department (
    id   INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(userId),
    title           VARCHAR(255) NOT NULL,
    creation_date   DATE DEFAULT (DATE('now'))
);

CREATE TABLE FAQ(
    id          INTEGER PRIMARY KEY,
    question        VARCHAR(255) NOT NULL,
    answer          TEXT NOT NULL,
    creation_date   DATE DEFAULT (DATE('now')),

    CHECK(length(question) > 0 and length(question) <= 200)
);

-------------------------------------------------------- TRIGGERS --------------------------------------------------------

CREATE TRIGGER update_ticket_date
AFTER UPDATE ON Ticket
FOR EACH ROW
BEGIN
    UPDATE Ticket SET update_date = DATE('now') WHERE id = OLD.id;
END;



-------------------------------------------------------- Populate the database --------------------------------------------------------








INSERT INTO User(id, username, email, password, name, is_agent, is_admin)
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

INSERT INTO Agent(id)
VALUES
(1),
(2),
(7),
(8),
(9),
(10);

INSERT INTO Admin(id)
VALUES
(3),
(4);

INSERT INTO Ticket(id, id_user, id_department, agent_assigned, title, content_text, ticket_status)
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

INSERT INTO Department (id, title) 
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


INSERT INTO FAQ (question, answer) VALUES
('What is a trouble ticket?', 'A trouble ticket is a record of a customer request for assistance.'),
('How do I create a new trouble ticket?', 'You can create a new trouble ticket by filling out a form on our website or by contacting our support team directly.'),
('What information should I include in a trouble ticket?', 'Include a detailed description of the issue you are experiencing, any error messages you have received, and steps you have taken to try to resolve the issue.'),
('How long does it take to resolve a trouble ticket?', 'The time it takes to resolve a trouble ticket depends on the nature of the issue and the resources available to our support team.'),
('How will I be notified when my trouble ticket is resolved?', 'You will receive an email notification when your trouble ticket is resolved.'),
('What is the status of my trouble ticket?', 'You can check the status of your trouble ticket by logging in to your account on our website and viewing the ticket status.'),
('Can I reopen a closed trouble ticket?', 'Yes, you can reopen a closed trouble ticket if the issue was not resolved to your satisfaction.'),
('What if I am not satisfied with the resolution of my trouble ticket?', 'If you are not satisfied with the resolution of your trouble ticket, you can contact our support team to discuss your concerns.'),
('How can I escalate a trouble ticket?', 'You can request that a trouble ticket be escalated by contacting our support team and providing details of the issue and why you believe it should be escalated.'),
('Can I attach files to a trouble ticket?', 'Yes, you can attach files to a trouble ticket to provide additional information or context for the issue.'),
('How do I check the history of a trouble ticket?', 'You can view the history of a trouble ticket by logging in to your account on our website and selecting the ticket in question.'),
('What is the difference between a trouble ticket and a service request?', 'A trouble ticket is a request for assistance with an issue or problem, while a service request is a request for a new service or change to an existing service.'),
('What is the priority of my trouble ticket?', 'The priority of a trouble ticket is based on the severity of the issue and the impact it has on your business.'),
('What is the SLA for resolving a trouble ticket?', 'The SLA for resolving a trouble ticket depends on the level of service agreement you have with our company.'),
('How do I provide feedback on my experience with the trouble ticket system?', 'You can provide feedback on your experience with the trouble ticket system by completing a survey that will be sent to you after your ticket is resolved.');

/* Enable foreign key constraint support */
PRAGMA foreign_keys = ON;