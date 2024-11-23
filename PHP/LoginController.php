<?php
require_once '../PHP/Database.php';
require_once '../PHP/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the form is submitted
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        die("Both email and password are required.");
    }

    // Create a database connection
    $database = new Database();
    $db = $database->getConnection();

    // Initialize User class
    $login = new Login($db);

    // Attempt to log in
    $loggedInUser = $login->login($email, $password);

    if ($loggedInUser) {
        // Set session variables
        $_SESSION['user_id'] = $loggedInUser['id'];  // Ensure the 'id' is being returned
        $_SESSION['username'] = $loggedInUser['username'];
        $_SESSION['role'] = $loggedInUser['role'];

        // Check the role and redirect accordingly
        if ($loggedInUser['role'] === 'job-seeker') {
            header("Location: ../HTML/jobseeker_dashboard.html");
        } elseif ($loggedInUser['role'] === 'employer') {
            header("Location: ../HTML/employer_registration.html");
        }
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>
