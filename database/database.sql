/* Delete existing tables */
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Department;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS User;

/* Create the tables */
CREATE TABLE User(
    id              INTEGER PRIMARY KEY,
    email           VARCHAR(255) UNIQUE NOT NULL,
    password            VARCHAR(255) NOT NULL,
    name            TEXT NOT NULL,
    is_agent        BOOLEAN NOT NULL DEFAULT 'false',
    is_admin        BOOLEAN NOT NULL DEFAULT 'false',
    creation_date   DATE DEFAULT (DATE('now')),
    update_date     DATE DEFAULT null,
    CHECK(email LIKE '%_@_%._%')
    -- CHECK(length(password) >= 6)
);

CREATE TABLE Agent(
    id INTEGER PRIMARY KEY REFERENCES User(id)
);

CREATE TABLE Admin (
    id INTEGER PRIMARY KEY REFERENCES User(id)
);

CREATE TABLE Department (
    id              INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(id),
    title           VARCHAR(255) NOT NULL,
    creation_date   DATE DEFAULT (DATE('now'))
);

CREATE TABLE Ticket (
    id              INTEGER PRIMARY KEY,
    id_user         INTEGER REFERENCES User(id),
    id_department   INTEGER REFERENCES Department(id),
    agent_assigned  INTEGER REFERENCES User(id) ON UPDATE CASCADE ON DELETE SET NULL,
    title           VARCHAR(255) NOT NULL,
    content_text    TEXT NOT NULL,
    ticket_status   VARCHAR(255) DEFAULT 'Open' NOT NULL,
    creation_date   DATE DEFAULT (DATE('now')),
    update_date     DATE DEFAULT null
    CHECK(length(content_text) > 0 and length(content_text) <= 200)
);

CREATE TABLE FAQ(
    id              INTEGER PRIMARY KEY,
    question        VARCHAR(255) NOT NULL,
    answer          TEXT NOT NULL,
    creation_date   DATE DEFAULT (DATE('now'))
    CHECK(length(question) > 0 and length(question) <= 200)
);

/* Enable foreign key constraint support */
PRAGMA foreign_keys = ON;


-------------------------------------------------------- TRIGGERS --------------------------------------------------------
-- does not work when db is updated using action (action_edit_ticket.php)
CREATE TRIGGER update_ticket_date
AFTER UPDATE ON Ticket
FOR EACH ROW
BEGIN
    UPDATE Ticket SET update_date = DATE('now') WHERE id = OLD.id;
END;



-------------------------------------------------------- Populate the database --------------------------------------------------------


INSERT INTO User(email, password, name, is_agent, is_admin) VALUES
('john@gmail.com', 'password1', 'John Doe', false, false),
('jane@gmail.com', 'password2', 'Jane Smith', false, false),
('alex@gmail.com', 'password3', 'Alex Johnson', true, false),
('carl@gmail.com', 'password4', 'Carl Williams', true, false),
('emma@gmail.com', 'password5', 'Emma Davis', false, true),
('ryan@gmail.com', 'password6', 'Ryan Garcia', false, true),
('lucy@gmail.com', 'password7', 'Lucy Brown', false, false),
('jacob@gmail.com', 'password8', 'Jacob Lee', true, false),
('olivia@gmail.com', 'password9', 'Olivia Wilson', true, false),
('michael@gmail.com', 'password10', 'Michael Jones', false, true),
('samantha@gmail.com', 'password11', 'Samantha Taylor', false, true),
('william@gmail.com', 'password12', 'William Green', true, false),
('ava@gmail.com', 'password13', 'Ava Anderson', true, false),
('matthew@gmail.com', 'password14', 'Matthew Hernandez', false, false),
('madison@gmail.com', 'password15', 'Madison Martin', false, false);

INSERT INTO Department(id_user, title) VALUES
(1, 'Sales'),
(2, 'Customer Service'),
(3, 'Technical Support'),
(4, 'Admin');


INSERT INTO Agent(id) VALUES (3), (4), (8), (9), (11), (12), (13);

INSERT INTO Admin(id) VALUES (5), (6), (10);

INSERT INTO Ticket(id_user, id_department, title, content_text) VALUES
(1, 1, 'Issue with payment', 'I am trying to purchase an item but my payment is not going through.'),
(2, 2, 'Forgot password', 'I forgot my password and cannot log into my account.'),
(3, 3, 'Internet connection issue', 'I am experiencing problems with my internet connection.'),
(4, 4, 'Cannot access my account', 'I cannot log into my account and need help accessing it.'),
(5, 1, 'Adding new user', 'I need help adding a new user to our system.'),
(6, 2, 'Removing user', 'I need help removing a user from our system.'),
(7, 3, 'Product inquiry', 'I have a question about a product you sell.'),
(8, 4, 'Change of address', 'I recently moved and need to update my address on file.'),
(9, 1, 'Software issue', 'I am having problems with the software and need help resolving it.'),
(10, 2, 'Billing inquiry', 'I have a question about my bill.'),
(11, 3, 'System upgrade', 'I need help upgrading our system.'),
(12, 4, 'Product warranty', 'I have a problem with a product I purchased and want to know about the warranty.'),
(13, 1, 'Cancellation request', 'I need to cancel my subscription.'),
(14, 2, 'Hardware issue', 'I am having problems with my hardware and need help)'),
(15, 3, 'Payment inquiry', 'I have a question about my payment.');

INSERT INTO FAQ (question, answer) VALUES ('What is your return policy?', 'Our return policy allows customers to return items within 30 days of purchase for a full refund.');
INSERT INTO FAQ (question, answer) VALUES ('How can I track my order?', 'You can track your order by logging into your account and viewing the order details. You will also receive a shipping confirmation email with a tracking number.');
INSERT INTO FAQ (question, answer) VALUES ('What forms of payment do you accept?', 'We accept Visa, Mastercard, American Express, and PayPal.');
INSERT INTO FAQ (question, answer) VALUES ('Do you offer international shipping?', 'Yes, we offer international shipping to select countries. Shipping rates and delivery times may vary.');
INSERT INTO FAQ (question, answer) VALUES ('How do I cancel an order?', 'To cancel an order, please contact customer service within 24 hours of placing the order. Once an order has been shipped, it cannot be cancelled.');
INSERT INTO FAQ (question, answer) VALUES ('Can I change my shipping address after I place an order?', 'If your order has not yet been shipped, you can contact customer service to request a change in shipping address. Once an order has been shipped, it cannot be rerouted.');
INSERT INTO FAQ (question, answer) VALUES ('What should I do if I receive a damaged item?', 'If you receive a damaged item, please contact customer service within 48 hours of receiving the item. We will provide instructions on how to return the item for a replacement or refund.');
INSERT INTO FAQ (question, answer) VALUES ('Do you offer gift wrapping?', 'Yes, we offer gift wrapping for an additional fee. You can select this option during checkout.');
INSERT INTO FAQ (question, answer) VALUES ('How do I change my password?', 'To change your password, log into your account and navigate to the account settings page. From there, you can update your password.');
INSERT INTO FAQ (question, answer) VALUES ('What is your customer service phone number?', 'Our customer service phone number is 1-800-123-4567.');
INSERT INTO FAQ (question, answer) VALUES ('Do you offer discounts for large orders?', 'Yes, we offer discounts for bulk orders. Please contact customer service for more information.');
INSERT INTO FAQ (question, answer) VALUES ('What is your privacy policy?', 'Our privacy policy outlines how we collect, use, and protect your personal information. You can view our privacy policy on our website.');
INSERT INTO FAQ (question, answer) VALUES ('Can I change the email address associated with my account?', 'Yes, you can change the email address associated with your account by navigating to the account settings page.');
INSERT INTO FAQ (question, answer) VALUES ('How long will it take for my order to arrive?', 'Delivery times vary depending on your location and shipping method. You can track the status of your order by logging into your account and viewing the order details.');
INSERT INTO FAQ (question, answer) VALUES ('What is your warranty policy?', 'Our warranty policy covers defects in materials and workmanship for a period of one year from the date of purchase. Please contact customer service if you have any issues with your product.');
