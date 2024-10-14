<?php  
include('../includes/header.php');
include('../includes/navbar.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session to access session variables
}

// Redirect to login if the user is not logged in or not an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Handling form submission to add a new course
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $course_description = $_POST['course_description'];

    // Database connection
    include('../config/db.php');

    // Prevent SQL Injection with prepared statements
    $query = "INSERT INTO courses (course_name, course_code, course_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $course_name, $course_code, $course_description);

    if ($stmt->execute()) {
        echo "<p>Course added successfully!</p>";
        header("Location: view_courses.php"); // Redirect to the courses list
        exit();
    } else {
        echo "<p>Error adding course. Please try again.</p>";
    }
}
?>

<div class="container">
    <h2>Add New Course</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="course_name">Course Name:</label>
            <input type="text" class="form-control" id="course_name" name="course_name" required>
        </div>
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <input type="text" class="form-control" id="course_code" name="course_code" required>
        </div>
        <div class="form-group">
            <label for="course_description">Course Description:</label>
            <textarea class="form-control" id="course_description" name="course_description" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>

    <div class="mt-3">
        <a href="view_courses.php" class="btn btn-secondary">Back to Course List</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
