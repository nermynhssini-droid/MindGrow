CREATE DATABASE mindgrow;
USE mindgrow;
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255)
);
CREATE TABLE certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    org_id INT,
    FOREIGN KEY (org_id) REFERENCES organizations(id)
);
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    content TEXT,
    cert_id INT,
    FOREIGN KEY (cert_id) REFERENCES certifications(id)
);
INSERT INTO organizations (name, image) VALUES
('Red Hat', 'redhat.jpg'),
('Cisco', 'cisco.jpg'),
('Microsoft', 'microsoft.jpg'),
('INE', 'ine.jpg');

INSERT INTO certifications (name, org_id) VALUES
('RHCSA', 1),
('RHCE', 1),
('CCNA', 2),
('Azure Fundamentals', 3),
('Pentesting Core', 4);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);
ALTER TABLE courses ADD file_path VARCHAR(255);
ALTER TABLE users ADD remember_token VARCHAR(255) NULL;
CREATE TABLE admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    target_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);