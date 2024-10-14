<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <?php

include('includes/navbar.php');

?>

<div class="container">
    <h1>Welcome to the Student Management System</h1>
    <p>Manage students, courses, and more efficiently.</p>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo "<p>You are logged in as " . $_SESSION['role'] . ".</p>";
        echo "<a href='logout.php' class='btn btn-danger'>Logout</a>";
    } else {
        echo "<a href='login.php' class='btn btn-primary'>Login</a> ";
        echo "<a href='signup.php' class='btn btn-success'>Signup</a>";
    }
    ?>
</div>

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>

