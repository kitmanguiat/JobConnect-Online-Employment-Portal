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

            // Set session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            if ($row['role'] === "job-seeker") {
                header('Location: ../JOBSEEKER/jobseeker_registration.php');
            } elseif ($row['role'] === "employer") {
                header('Location: ../EMPLOYER/employer_registration.php');
            }
            exit; // Stop further script execution after redirection
        }
    }

    if (!$isAuthenticated) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid email or password.',
                icon: 'error'
            }).then(() => {
                window.location.href = '../HTML/main_login.html';
            });
        </script>";
    }
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'Error!',
            text: 'No users found in the system.',
            icon: 'error'
        }).then(() => {
            window.location.href = '../HTML/main_login.html';
        });
    </script>";
}
?>
