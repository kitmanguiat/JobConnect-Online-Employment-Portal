<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../SIGNUP/crudUsers.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnect();

// Initialize User class
$user = new User($db);

// Get input data from form
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));

// Fetch all users
$stmt = $user->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $isAuthenticated = false; // Flag for authentication success

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verify email and password
        if ($email === $row['email'] && password_verify($password, $row['password'])) {
            $isAuthenticated = true; // Mark as authenticated
            $role = $row['role'];

            // Redirect based on role
            if ($role === "job-seeker") {
                header('Location: ../JOBSEEKER/jobseeker_registration.php');
            } elseif ($role === "employer") {
                header('Location: ../EMPLOYER/employer_registration.php');
            }
            exit; // Stop further script execution after redirection
        }
    }

    if (!$isAuthenticated) {
        echo "Invalid email or password.";
    }
} else {
    echo "No users found in the system.";
}
?>
