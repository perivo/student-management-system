-- Create database 
CREATE DATABASE IF NOT EXISTS student_management;

-- Use the created database
USE student_management;

-- Table for users (both admins and students will be stored here)
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    course VARCHAR(100) DEFAULT NULL, -- NULL for admins
    role ENUM('Admin', 'Student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for courses
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(50) NOT NULL UNIQUE,
    course_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to link students and courses (many-to-many relationship)
CREATE TABLE IF NOT EXISTS student_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE, -- Linked to users table (for students)
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin credentials (change credentials accordingly)
INSERT INTO users (first_name, last_name, dob, gender, email, phone, address, course, role) 
VALUES ('Admin', 'User', '1970-01-01', 'Male', 'admin@example.com', '1234567890', 'Admin Address', NULL, 'Admin');

-- Insert sample student (for testing purposes)
INSERT INTO users (first_name, last_name, dob, gender, email, phone, address, course, role) 
VALUES ('John', 'Doe', '2000-05-15', 'Male', 'john@example.com', '9876543210', 'Student Address', 'Computer Science', 'Student');

-- Sample Courses (for testing purposes)
INSERT INTO courses (course_name, course_code, course_description) 
VALUES ('Computer Science', 'CS101', 'Introduction to Computer Science'),
       ('Mathematics', 'MATH101', 'Basic Mathematics');

-- Enroll the sample student into a course
INSERT INTO student_courses (student_id, course_id) 
VALUES ((SELECT id FROM users WHERE email = 'john@example.com'), (SELECT id FROM courses WHERE course_code = 'CS101'));
