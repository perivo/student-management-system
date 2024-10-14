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

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Database connection
    include('../config/db.php');

    // Fetch student details
    $query = "SELECT * FROM users WHERE id = ? AND role = 'Student'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "<p>Student not found!</p>";
        exit();
    }

    // Fetch all courses
    $query_courses = "SELECT * FROM courses";
    $stmt_courses = $conn->prepare($query_courses);
    $stmt_courses->execute();
    $courses_result = $stmt_courses->get_result();

    // Assign course to student when form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['courses'])) {
            $selected_courses = $_POST['courses']; // This is the array of selected course IDs

            // Prepare the query to prevent SQL Injection
            $assign_query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
            $stmt_assign = $conn->prepare($assign_query);

            // Bind both student_id and course_id
            foreach ($selected_courses as $course_id) {
                $stmt_assign->bind_param("ii", $student_id, $course_id);
                if ($stmt_assign->execute()) {
                    echo "<p>Student with ID $student_id assigned to course successfully!</p>";
                } else {
                    echo "<p>Error assigning student with ID $student_id: " . $stmt_assign->error . "</p>";
                }
            }

            // Redirect to students list after assignment
            header("Location: view_students.php");
            exit();
        } else {
            echo "<p>No courses selected. Please select at least one course.</p>";
        }
    }
} else {
    echo "<p>Invalid request. Student ID missing.</p>";
    exit();
}
?>

<div class="container">
    <h2>Assign Courses to <?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?></h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="courses">Select Courses:</label>
            <select class="form-control" name="courses[]" multiple required>
                <?php 
                while ($course = $courses_result->fetch_assoc()) {
                    echo '<option value="' . $course['id'] . '">' . htmlspecialchars($course['course_name']) . ' (' . htmlspecialchars($course['course_code']) . ')</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Courses</button>
    </form>

    <div class="mt-3">
        <a href="view_students.php" class="btn btn-secondary">Back to Student List</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
