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
} // Start session to access session variables

// Redirect to login if the user is not logged in or not a student
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Student') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email']; // Fetch the logged-in student's email

// Database connection
include('config/db.php');

// Prevent SQL Injection by using prepared statements
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "<p>Student details not found!</p>";
    exit();
}

// Fetch assigned courses
$query_courses = "SELECT c.course_name, c.course_code 
                  FROM courses c 
                  JOIN student_courses sc ON c.id = sc.course_id 
                  WHERE sc.student_id = ?";
$stmt_courses = $conn->prepare($query_courses);
$stmt_courses->bind_param("i", $student['id']);
$stmt_courses->execute();
$courses_result = $stmt_courses->get_result();

// Fetch all available courses for enrollment
$query_all_courses = "SELECT * FROM courses";
$stmt_all_courses = $conn->prepare($query_all_courses);
$stmt_all_courses->execute();
$all_courses_result = $stmt_all_courses->get_result();
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($student['first_name']); ?>!</h2>
    <p>Here is your student dashboard. You can manage your courses and view your details.</p>
    
    <ul class="list-group">
        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
        <li class="list-group-item"><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></li>
        <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></li>
    </ul>

    <div class="mt-3">
        <a href="edit_student.php" class="btn btn-warning">Edit Details</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="mt-4">
        <h4>Your Assigned Courses:</h4>
        <ul class="list-group">
            <?php 
            if ($courses_result->num_rows > 0) {
                while ($course = $courses_result->fetch_assoc()) {
                    echo '<li class="list-group-item">' . htmlspecialchars($course['course_name']) . ' (' . htmlspecialchars($course['course_code']) . ')</li>';
                }
            } else {
                echo '<li class="list-group-item">You are not enrolled in any courses yet.</li>';
            }
            ?>
        </ul>
    </div>

   
</div>

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
