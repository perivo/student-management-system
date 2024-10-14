<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session to access session variables
}

// Define base URL for the project to avoid duplicating paths
$base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/student-management-system/';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php echo $base_url; ?>index.php">SMS</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <!-- Home Link -->
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>index.php">Home</a>
      </li>

      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
        <!-- For logged-in users -->
        <?php if ($_SESSION['role'] == 'Admin'): ?>
          <!-- Admin navigation links -->
          <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_students.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo $base_url; ?>students/view_students.php">View Students</a>
          </li>
          <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_courses.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo $base_url; ?>courses/view_courses.php">View Courses</a>
          </li>
          <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo $base_url; ?>admin_dashboard.php">Admin Dashboard</a>
          </li>
        <?php elseif ($_SESSION['role'] == 'Student'): ?>
          <!-- Student navigation links -->
          <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'student_dashboard.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo $base_url; ?>student_dashboard.php">Student Dashboard</a>
          </li>
          <!-- Change the "View My Courses" link to "Show Enrolled Courses" -->
          <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'show_enrolled_courses.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo $base_url; ?>courses/show_enrolled_courses.php">Show Enrolled Courses</a>
          </li>
        <?php endif; ?>
        <!-- Logout link for all logged-in users -->
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $base_url; ?>logout.php">Logout</a>
        </li>
      <?php else: ?>
        <!-- For not logged-in users -->
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>">
          <a class="nav-link" href="<?php echo $base_url; ?>login.php">Login</a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'signup.php') ? 'active' : ''; ?>">
          <a class="nav-link" href="<?php echo $base_url; ?>signup.php">Signup</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Bootstrap Bundle JS (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
