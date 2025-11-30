CREATE DATABASE IF NOT EXISTS librarydb;
USE librarydb;

-- USERS TABLE
CREATE TABLE users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    mobile VARCHAR(10) NOT NULL
);

-- CATEGORY TABLE
CREATE TABLE category (
    cat_code INT AUTO_INCREMENT PRIMARY KEY,
    cat_desc VARCHAR(100) NOT NULL
);

-- BOOKS TABLE
CREATE TABLE books (
    isbn VARCHAR(20) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    cat_code INT NOT NULL,
    FOREIGN KEY (cat_code) REFERENCES category(cat_code)
);

-- RESERVED BOOKS TABLE
CREATE TABLE reserved_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    reserved_date DATE NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (isbn) REFERENCES books(isbn),
    UNIQUE KEY uniq_isbn (isbn)  -- only one active reservation per book
);

-- SAMPLE DATA
INSERT INTO category (cat_desc) VALUES
('Fiction'),
('Business'),
('Computer Science'),
('History');

INSERT INTO books (isbn, title, author, cat_code) VALUES
('9780000000001', 'The Great Novel', 'John Writer', 1),
('9780000000002', 'Startup 101', 'Jane Founder', 2),
('9780000000003', 'Intro to Algorithms', 'T. Coder', 3),
('9780000000004', 'PHP for Web', 'P. Dev', 3),
('9780000000005', 'World War II', 'H. Historian', 4),
('9780000000006', 'Advanced Business', 'B. Boss', 2),
('9780000000007', 'Database Design', 'D. Schema', 3);
