

# Student Management System (SMS)

This is a **Student Management System** (SMS) web application, developed to help manage students, courses, and enrollments. The system allows **admins** to manage students and courses, assign students to courses, and view their information. **Students** can view their dashboard and check their enrolled courses.

## Features

### Admin
- View, add, and manage students
- View, add, and manage courses
- Assign students to courses
- Manage student data and perform CRUD operations

### Student
- View enrolled courses
- Access personal dashboard with relevant information

### Authentication
- Role-based access control with **Admin** and **Student** roles
- Secure login and logout functionality

### General
- Responsive design using **Bootstrap**
- **MySQL** for database management
- **PHP** for backend logic and data handling

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Additional Libraries**: Bootstrap, DataTables (for better table management)

## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

---

## Project Structure

```bash
student-management-system/
│
├── includes/               # Common includes (header, navbar, footer, config)
├── students/               # Student-related pages (view, add, update, etc.)
├── courses/                # Course-related pages (view, add, etc.)
├── assets/                 # Static assets (CSS, JS, images)
├── config/                 # Database configuration files
├── admin_dashboard.php      # Admin dashboard page
├── student_dashboard.php    # Student dashboard page
├── login.php               # Login page
├── signup.php              # Signup page (optional)
├── logout.php              # Logout script
└── README.md               # Project documentation
```

---

## Database Schema

The following schema is required for the project. This creates necessary tables for users, courses, and enrollments.

### `users` Table
This table stores information for both students and admins.

```sql
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    course VARCHAR(100) DEFAULT NULL, -- NULL for Admins
    role ENUM('Admin', 'Student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### `courses` Table
This table stores course details.

```sql
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(50) NOT NULL UNIQUE,
    course_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### `student_courses` Table
This table links students to their enrolled courses.

```sql
CREATE TABLE IF NOT EXISTS student_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Installation

### Prerequisites
- **XAMPP or WAMP** (or any local PHP/MySQL server)
- **Composer** (optional, if using additional PHP libraries)
- Web browser (Chrome, Firefox, etc.)

### Steps to Set Up the Project

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ivopereira/student-management-system.git
   ```
   Navigate to the project directory:
   ```bash
   cd student-management-system
   ```

2. **Set Up the Database**
   - Open **phpMyAdmin** and create a new database named `student_management`.
   - Import the provided SQL schema located in `schema.sql` into the database.
   - Alternatively, use this SQL command to import:
     ```bash
     mysql -u your_username -p student_management < schema.sql
     ```

3. **Update Database Configuration**
   - Open `config/db.php` and update the database connection settings:
     ```php
     $conn = new mysqli("localhost", "your_db_username", "your_db_password", "student_management");
     ```

4. **Start XAMPP/WAMP** and navigate to `http://localhost/student-management-system` in your browser.

5. **Login**
   - Admin credentials: 
     - **Username**: `admin@example.com`
     - **Password**: `password` (MD5-hashed in the database)
   - Default Student credentials:
     - **Username**: `john@example.com`

6. **Modify Admin Credentials**
   After logging in as the admin, go to the Admin Dashboard and change the default password for security.

---

## Usage

- **Admin** can view, add, and manage students and courses.
- **Student** can log in to view their dashboard and see their enrolled courses.
- This project serves as a foundational system for student and course management, with easy extensibility to add more features, such as grading, attendance, and more.

---

## Contributing

1. Fork the project.
2. Create a new branch.
3. Make your changes.
4. Submit a pull request.

We welcome contributions to improve this system, add features, or fix bugs.

---

## License

This project is licensed under the MIT License.

---

You can now upload this `README.md` file to your GitHub repository at `https://github.com/perivo/student-management-system`.