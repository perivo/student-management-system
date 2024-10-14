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

// Fetch all students
$query = "SELECT * FROM users WHERE role = 'Student' ORDER BY first_name, last_name";
$stmt = $conn->prepare($query);
$stmt->execute();
$students_result = $stmt->get_result();

?>

<div class="container">
    <h2>All Students</h2>

    <?php
    if ($students_result->num_rows > 0) {
        echo '
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';

        // Fetch and display students
        while ($student = $students_result->fetch_assoc()) {
            echo '<tr>
                    <td>' . htmlspecialchars($student['first_name']) . '</td>
                    <td>' . htmlspecialchars($student['last_name']) . '</td>
                    <td>' . htmlspecialchars($student['email']) . '</td>
                    <td>
                        <a href="assign_student_course.php?student_id=' . $student['id'] . '" class="btn btn-info btn-sm" onclick="return confirm(\'Are you sure you want to assign courses to ' . htmlspecialchars($student['first_name']) . '?\')">Assign Courses</a>
                    </td>
                </tr>';
        }

        echo '</tbody>
            </table>';
    } else {
        echo '<p>No students found.</p>';
    }
    ?>
    
    <div class="mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php
include('../includes/footer.php');
?>
