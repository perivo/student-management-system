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

// Insert student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = 'Student'; // Set role as Student

    // Insert the student into the users table
    $sql = "INSERT INTO users (first_name, last_name, email, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $role);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New student added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding student: " . $conn->error . "</div>";
    }
}

?>

<div class="container">
    <h2>Add Student</h2>
    <form action="add_student.php" method="POST">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
