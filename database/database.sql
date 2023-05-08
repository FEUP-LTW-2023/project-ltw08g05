/* Delete existing tables */
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Department;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Country;
DROP TABLE IF EXISTS AccessLog;
/* Create the tables */
CREATE TABLE Country (
    id         INTEGER PRIMARY KEY,
    name       VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE User(
    id              INTEGER PRIMARY KEY,
    email           VARCHAR(20) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    first_name      VARCHAR(20) NOT NULL,
    last_name       VARCHAR(20) NOT NULL,
    username        VARCHAR(20) UNIQUE NOT NULL,
    address         VARCHAR(255) NOT NULL,
    country_id      INTEGER NOT NULL,
    city            VARCHAR(20) NOT NULL,
    zip_code        VARCHAR(8) NOT NULL, -- 4100-001
    bio             TEXT,
    is_agent        BOOLEAN NOT NULL DEFAULT 'false',
    is_admin        BOOLEAN NOT NULL DEFAULT 'false',
    creation_date   DATE DEFAULT (DATE('now')),
    update_date     DATE DEFAULT null,
    CHECK(email LIKE '%_@_%._%'),
    CHECK(length(password) >= 6),
    FOREIGN KEY (country_id) REFERENCES Country(id)
);


CREATE TABLE AccessLog (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    ip_address VARCHAR(255),
    operating_system VARCHAR(255),
    access_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User (id)
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

INSERT INTO Country (name) VALUES
  ('United States'),
  ('Canada'),
  ('Mexico'),
  ('Brazil'),
  ('Argentina'),
  ('United Kingdom'),
  ('France'),
  ('Germany'),
  ('Spain'),
  ('Italy'),
  ('Netherlands'),
  ('Belgium'),
  ('Switzerland'),
  ('Austria'),
  ('Sweden');

INSERT INTO User (email, password, first_name, last_name, username, address, country_id, city, zip_code, bio) VALUES
    ('user1@example.com', 'password1', 'John', 'Doe', 'johndoe', '123 Main St', 1, 'Los Angeles', '90001', 'Bio for John Doe'),
    ('user2@example.com', 'password2', 'Jane', 'Doe', 'janedoe', '456 Main St', 2, 'New York', '10001', 'Bio for Jane Doe'),
    ('user3@example.com', 'password3', 'Bob', 'Smith', 'bobsmith', '789 Main St', 3, 'Chicago', '60601', 'Bio for Bob Smith'),
    ('user4@example.com', 'password4', 'Alice', 'Johnson', 'alicejohnson', '321 Main St', 4, 'Houston', '77001', 'Bio for Alice Johnson'),
    ('user5@example.com', 'password5', 'Michael', 'Brown', 'michaelbrown', '654 Main St', 5, 'Phoenix', '85001', 'Bio for Michael Brown'),
    ('user6@example.com', 'password6', 'Samantha', 'Davis', 'samanthadavis', '987 Main St', 6, 'Philadelphia', '19101', 'Bio for Samantha Davis'),
    ('user7@example.com', 'password7', 'David', 'Wilson', 'davidwilson', '246 Main St', 7, 'San Antonio', '78201', 'Bio for David Wilson'),
    ('user8@example.com', 'password8', 'Linda', 'Garcia', 'lindagarcia', '135 Main St', 8, 'San Diego', '92101', 'Bio for Linda Garcia'),
    ('user9@example.com', 'password9', 'William', 'Martinez', 'williammartinez', '864 Main St', 9, 'Dallas', '75201', 'Bio for William Martinez'),
    ('user10@example.com', 'password10', 'Emily', 'Hernandez', 'emilyhernandez', '975 Main St', 10, 'San Jose', '95101', 'Bio for Emily Hernandez'),
    ('user11@example.com', 'password11', 'Christopher', 'Lopez', 'christopherlopez', '732 Main St', 11, 'Austin', '73301', 'Bio for Christopher Lopez'),
    ('user12@example.com', 'password12', 'Mary', 'Clark', 'maryclark', '741 Main St', 12, 'Jacksonville', '32201', 'Bio for Mary Clark'),
    ('user13@example.com', 'password13', 'Daniel', 'Lee', 'daniellee', '258 Main St', 13, 'Fort Worth', '76101', 'Bio for Daniel Lee'),
    ('user14@example.com', 'password14', 'Patricia', 'Walker', 'patriciawalker', '369 Main St', 14, 'Columbus', '43201', 'Bio for Patricia Walker'),
    ('user15@example.com', 'password15', 'Joseph', 'Perez', 'josephperez', '159 Main St', 15, 'San Francisco', '94101', 'Bio for Joseph Perez');


INSERT INTO Department(id_user, title) VALUES
(1, 'Sales'),
(2, 'Customer Service'),
(3, 'Technical Support'),
(4, 'Technical Support'),
(5, 'Admin'),
(6, 'Admin'),
(7, 'Sales'),
(8, 'Customer Service'),
(9, 'Technical Support'),
(10, 'Admin'),
(11, 'Admin'),
(12, 'Sales'),
(13, 'Customer Service'),
(14, 'Technical Support'),
(15, 'Admin');

INSERT INTO Agent(id) VALUES (3), (4), (8), (9), (11), (12), (13);

INSERT INTO Admin(id) VALUES (5), (6), (10);

INSERT INTO Ticket(id_user, id_department, title, content_text) VALUES
(1, 1, 'Issue with payment', 'I am trying to purchase an item but my payment is not going through.'),
(2, 2, 'Forgot password', 'I forgot my password and cannot log into my account.'),
(3, 3, 'Internet connection issue', 'I am experiencing problems with my internet connection.'),
(4, 4, 'Cannot access my account', 'I cannot log into my account and need help accessing it.'),
(5, 5, 'Adding new user', 'I need help adding a new user to our system.'),
(6, 6, 'Removing user', 'I need help removing a user from our system.'),
(7, 1, 'Product inquiry', 'I have a question about a product you sell.'),
(8, 2, 'Change of address', 'I recently moved and need to update my address on file.'),
(9, 3, 'Software issue', 'I am having problems with the software and need help resolving it.'),
(10, 5, 'Billing inquiry', 'I have a question about my bill.'),
(11, 6, 'System upgrade', 'I need help upgrading our system.'),
(12, 1, 'Product warranty', 'I have a problem with a product I purchased and want to know about the warranty.'),
(13, 2, 'Cancellation request', 'I need to cancel my subscription.'),
(14, 3, 'Hardware issue', 'I am having problems with my hardware and need help)'),
(15, 5, 'Payment inquiry', 'I have a question about my payment.');

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
