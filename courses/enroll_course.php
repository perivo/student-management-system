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

// Check if student_id is set and valid
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    echo "<p>Invalid request. Student ID is missing or incorrect.</p>";
    exit();
}

$student_id = $_GET['student_id'];

// Database connection
include('../config/db.php');

// Fetch the student's information (optional, for displaying the student name, etc.)
$query = "SELECT * FROM users WHERE id = ? AND role = 'Student'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows === 0) {
    echo "<p>Student not found!</p>";
    exit();
}

// Enroll student in a course (when form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];  // Assuming course ID is sent via POST
    if (!empty($course_id)) {
        // Insert the student-course relationship into the student_courses table
        $enroll_query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
        $stmt = $conn->prepare($enroll_query);
        $stmt->bind_param("ii", $student_id, $course_id);
        
        if ($stmt->execute()) {
            echo "<p>Student enrolled in course successfully!</p>";
        } else {
            echo "<p>Error enrolling student: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>Please select a course to enroll the student in.</p>";
    }
}

// Fetch available courses
$course_query = "SELECT * FROM courses";
$course_stmt = $conn->prepare($course_query);
$course_stmt->execute();
$courses_result = $course_stmt->get_result();
?>

<div class="container">
    <h2>Enroll Student</h2>

    <p>Enrolling Student: <?php echo htmlspecialchars($student_result->fetch_assoc()['first_name']); ?></p>

    <form method="POST" action="">
        <div class="form-group">
            <label for="course_id">Select Course:</label>
            <select class="form-control" name="course_id" required>
                <option value="">Select a course</option>
                <?php
                while ($course = $courses_result->fetch_assoc()) {
                    echo '<option value="' . $course['id'] . '">' . htmlspecialchars($course['course_name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enroll Student</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
