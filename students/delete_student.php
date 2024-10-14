<?php 
include('../includes/header.php');
include('../includes/navbar.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Start session to access session variables

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
    $delete_query = "DELETE FROM users WHERE id = ? AND role = 'Student'";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        echo "<p>Student deleted successfully!</p>";
        header("Location: view_students.php"); // Redirect back to the student list
        exit();
    } else {
        echo "<p>Error deleting student. Please try again.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
    exit();
}

include('../includes/footer.php');
?>
