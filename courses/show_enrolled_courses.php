<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session to access session variables
}

include('../config/db.php'); // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Enrolled Courses</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('../includes/navbar.php'); ?>

<div class="container">
    <h2>Show Enrolled Courses</h2>

    <?php 
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['role'] == 'Student') {
        $email = $_SESSION['email'];
        
        // Fetch student details
        $query_student = "SELECT id, first_name FROM users WHERE email = ?";
        $stmt_student = $conn->prepare($query_student);
        $stmt_student->bind_param("s", $email);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();
        $student = $result_student->fetch_assoc();

        if (!$student) {
            echo "<p>Student details not found!</p>";
            exit();
        }

        // Fetch enrolled courses
        $query_enrolled_courses = "SELECT c.course_name, c.course_code 
                                   FROM courses c 
                                   JOIN student_courses sc ON c.id = sc.course_id 
                                   WHERE sc.student_id = ?";
        $stmt_enrolled_courses = $conn->prepare($query_enrolled_courses);
        $stmt_enrolled_courses->bind_param("i", $student['id']);
        $stmt_enrolled_courses->execute();
        $enrolled_courses_result = $stmt_enrolled_courses->get_result();
        ?>

        <h4>Your Enrolled Courses:</h4>
        <ul class="list-group">
            <?php 
            if ($enrolled_courses_result->num_rows > 0) {
                while ($course = $enrolled_courses_result->fetch_assoc()) {
                    echo '<li class="list-group-item">' . htmlspecialchars($course['course_name']) . ' (' . htmlspecialchars($course['course_code']) . ')</li>';
                }
            } else {
                echo '<li class="list-group-item">You are not enrolled in any courses yet.</li>';
            }
            ?>
        </ul>

        <hr>

        <!-- Show available courses for enrollment -->
       

    <?php 
    } else {
        echo "<p>You must be logged in as a student to view this page.</p>";
    }
    ?>

</div>

<!-- Bootstrap Bundle JS (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
