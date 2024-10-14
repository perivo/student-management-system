<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body></body>
<?php  
include('../includes/navbar.php');

// Start session only if it's not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session to access session variables
}

// Redirect to login if the user is not logged in or not an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Database connection
    include('../config/db.php');

    // Prevent SQL Injection by using prepared statements
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

    // Handling form submission to update student details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $course = $_POST['course'];
        $address = $_POST['address'];

        // Prevent SQL Injection with prepared statements
        $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, course = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $course, $address, $student_id);

        if ($stmt->execute()) {
            echo "<p>Student updated successfully!</p>";
            header("Location: view_students.php"); // Redirect back to view students
            exit();
        } else {
            echo "<p>Error updating student. Please try again.</p>";
        }
    }
} else {
    echo "<p>Invalid request.</p>";
    exit();
}
?>

<div class="container">
    <h2>Edit Student Details</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="course" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Student</button>
    </form>

    <div class="mt-3">
        <a href="view_students.php" class="btn btn-secondary">Back to Student List</a>
    </div>
</div>


<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>
