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
include('includes/navbar.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session to access session variables
}
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

// Handling form submission to update student details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $address = $_POST['address'];
    
    // Prevent SQL Injection with prepared statements
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, dob = ?, gender = ?, course = ?, address = ? WHERE email = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssss", $first_name, $last_name, $dob, $gender, $course, $address, $email);
    
    if ($stmt->execute()) {
        echo "<p>Profile updated successfully!</p>";
        header("Location: student_dashboard.php"); // Redirect to the student dashboard
        exit();
    } else {
        echo "<p>Error updating profile. Please try again.</p>";
    }
}
?>

<div class="container">
    <h2>Edit Your Details</h2>

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
            <label for="dob">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="course" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Details</button>
    </form>

    <div class="mt-3">
        <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>