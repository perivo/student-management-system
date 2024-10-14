<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <?php
include('includes/navbar.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if the user is not logged in or not an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

?>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin! You can manage students and courses from here.</p>

    <div class="row">
        <!-- Manage Students -->
        <div class="col-md-6">
            <h3>Manage Students</h3>
            <a href="students/view_students.php" class="btn btn-primary mb-3">View Students</a>
            <a href="students/add_student.php" class="btn btn-success mb-3">Add New Student</a>
        </div>

        <!-- Manage Courses -->
        <div class="col-md-6">
            <h3>Manage Courses</h3>
            <a href="courses/view_courses.php" class="btn btn-primary mb-3">View Courses</a>
            <a href="courses/add_course.php" class="btn btn-success mb-3">Add New Course</a>
        </div>
    </div>

   
</div>


<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
