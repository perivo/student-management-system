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

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Database connection
    include('../config/db.php');

    // Fetch course details
    $query = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if (!$course) {
        echo "<div class='container'><p>Course not found!</p></div>";
        exit();
    }

    // Fetch all students (from 'users' table where role = 'Student')
    $query_students = "SELECT * FROM users WHERE role = 'Student'";
    $stmt_students = $conn->prepare($query_students);
    $stmt_students->execute();
    $students_result = $stmt_students->get_result();

    // Check if any students are found
    if ($students_result->num_rows == 0) {
        echo "<div class='container'><p>No students found to assign to this course.</p></div>";
        exit();
    }

    // Assign students to course when form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['students'])) {
            $selected_students = $_POST['students']; // This is the array of selected student IDs

            // Prepare the query to prevent SQL Injection
            $assign_query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
            $stmt = $conn->prepare($assign_query);

            $assign_success = true;

            // Bind both student_id and course_id
            foreach ($selected_students as $student_id) {
                $stmt->bind_param("ii", $student_id, $course_id);
                if (!$stmt->execute()) {
                    $assign_success = false;
                    echo "<p>Error assigning student with ID $student_id: " . $stmt->error . "</p>";
                }
            }

            if ($assign_success) {
                echo "<p>Selected students have been successfully assigned to the course.</p>";
                // Optionally, redirect after a few seconds:
                // header("refresh:2;url=view_courses.php");
            }
        } else {
            echo "<p>No students selected. Please select at least one student.</p>";
        }
    }
} else {
    echo "<div class='container'><p>Invalid request. Course ID missing.</p></div>";
    exit();
}
?>

<div class="container">
    <h2>Assign Students to <?php echo htmlspecialchars($course['course_name']); ?> (<?php echo htmlspecialchars($course['course_code']); ?>)</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="students">Select Students:</label>
            <select class="form-control" name="students[]" multiple required>
                <?php 
                while ($student = $students_result->fetch_assoc()) {
                    echo '<option value="' . $student['id'] . '">' . htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Students</button>
    </form>

    <div class="mt-3">
        <a href="view_courses.php" class="btn btn-secondary">Back to Course List</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
