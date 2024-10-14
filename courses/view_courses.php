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

// Database connection
include('../config/db.php');

// Fetch all courses using prepared statement
$query = "SELECT * FROM courses";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="container">
            <h2>All Courses</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Course Code</th>
                        <th>Course Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
    
    while ($course = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($course['course_name']) . '</td>
                <td>' . htmlspecialchars($course['course_code']) . '</td>
                <td>' . htmlspecialchars($course['course_description']) . '</td>
                <td>
                    <a href="assign_student_course.php?course_id=' . $course['id'] . '" class="btn btn-info btn-sm">Assign Students</a>
                </td>
            </tr>';
    }

    echo '</tbody>
          </table>
          </div>';
} else {
    echo '<p>No courses found.</p>';
}

include('../includes/footer.php');
?>
