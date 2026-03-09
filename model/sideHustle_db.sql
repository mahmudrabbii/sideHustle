CREATE DATABASE sideHustle_db;
USE sideHustle_db;

CREATE TABLE professionals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    first_profession VARCHAR(100) NOT NULL,
    expertise_area VARCHAR(100) NOT NULL,
    experience_years INT DEFAULT 0,
    description LONGTEXT NOT NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    availability VARCHAR(50) NOT NULL,
    area_of_operation VARCHAR(100) NOT NULL
);

CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    area_of_work VARCHAR(100) NOT NULL
);

CREATE TABLE service_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    professional_id INT,
    service_type VARCHAR(100),
    request_description TEXT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (professional_id) REFERENCES professionals(id)
);

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    user_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO professionals 
(full_name, email, phone, first_profession, expertise_area, experience_years, description, hourly_rate, availability, area_of_operation)
VALUES
('Rahim Uddin', 'rahim@gmail.com', '01711111111', 'School Teacher', 'Graphic Design', 4, 'Experienced in logo design, banners and social media graphics.', 15.00, 'Evening', 'Dhaka'),

('Nusrat Jahan', 'nusrat@gmail.com', '01722222222', 'Bank Officer', 'Web Development', 3, 'Builds responsive websites using HTML, CSS and PHP.', 20.00, 'Weekend', 'Gazipur'),

('Tanvir Hasan', 'tanvir@gmail.com', '01733333333', 'Mechanical Engineer', 'AutoCAD Design', 5, 'Provides 2D and 3D AutoCAD design services for projects.', 25.00, 'Flexible', 'Dhaka'),

('Sadia Islam', 'sadia@gmail.com', '01744444444', 'Student', 'Content Writing', 2, 'Writes blog posts, SEO articles and website content.', 10.00, 'Evening', 'Narsingdi'),

('Mahmud Hossain', 'mahmud@gmail.com', '01755555555', 'Software Developer', 'Mobile App Development', 6, 'Develops Android apps using Java and Flutter.', 30.00, 'Flexible', 'Dhaka');

INSERT INTO clients
(full_name, email, phone, area_of_work)
VALUES
('Karim Ahmed', 'karim@gmail.com', '01811111111', 'Dhaka'),

('Shila Akter', 'shila@gmail.com', '01822222222', 'Gazipur'),

('Fahim Chowdhury', 'fahim@gmail.com', '01833333333', 'Dhaka'),

('Rafiul Islam', 'rafiul@gmail.com', '01844444444', 'Narsingdi'),

('Tania Sultana', 'tania@gmail.com', '01855555555', 'Dhaka');

INSERT INTO service_requests
(client_id, professional_id, service_type, request_description, status)
VALUES
(1, 1, 'Graphic Design', 'Need a logo and banner for my online store.', 'Pending'),

(2, 2, 'Web Development', 'Looking for a responsive website for my business.', 'Accepted'),

(3, 3, 'AutoCAD Design', 'Require AutoCAD drawings for a small construction project.', 'Pending'),

(4, 4, 'Content Writing', 'Need SEO blog articles for my education website.', 'Completed'),

(5, 5, 'Mobile App Development', 'Want an Android app for my e-commerce platform.', 'Pending');

INSERT INTO users
(email, password, full_name, user_type)
VALUES
('rahim@gmail.com', 'password123', 'Rahim Uddin', 'professional'),
('nusrat@gmail.com', 'password123', 'Nusrat Jahan', 'professional'),
('tanvir@gmail.com', 'password123', 'Tanvir Hasan', 'professional'),
('sadia@gmail.com', 'password123', 'Sadia Islam', 'professional'),
('mahmud@gmail.com', 'password123', 'Mahmud Hossain', 'professional'),
('karim@gmail.com', 'password123', 'Karim Ahmed', 'client'),
('shila@gmail.com', 'password123', 'Shila Akter', 'client'),
('fahim@gmail.com', 'password123', 'Fahim Chowdhury', 'client'),
('rafiul@gmail.com', 'password123', 'Rafiul Islam', 'client'),
('tania@gmail.com', 'password123', 'Tania Sultana', 'client');




