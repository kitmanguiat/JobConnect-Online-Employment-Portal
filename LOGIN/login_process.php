<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$stmtUsers = $user->read(); // Changed variable name to avoid conflicts
$num = $stmtUsers->rowCount();

if ($num > 0) {
    $isAuthenticated = false; // Flag for authentication success

    while ($row = $stmtUsers->fetch(PDO::FETCH_ASSOC)) {
        // Verify email and password
        if ($email === $row['email'] && password_verify($password, $row['password'])) {
            $isAuthenticated = true; // Mark as authenticated

            // Set session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Log session variables for debugging
            file_put_contents('debug_log.txt', "User authenticated: " . print_r($_SESSION, true), FILE_APPEND);

            // Check if the user is a job-seeker or employer
            if ($row['role'] === "job-seeker") {
                // Check if job-seeker profile exists
                $jobSeekerQuery = "SELECT job_seeker_id FROM job_seekers WHERE user_id = :user_id";
                $stmtJobSeeker = $db->prepare($jobSeekerQuery); // Changed variable name
                $stmtJobSeeker->bindParam(':user_id', $row['user_id']);
                $stmtJobSeeker->execute();

                if ($stmtJobSeeker->rowCount() > 0) {
                    // Job-seeker profile exists, redirect to dashboard
                    $_SESSION['job_seeker_id'] = $stmtJobSeeker->fetch(PDO::FETCH_ASSOC)['job_seeker_id'];
                    header('Location: ../JOBSEEKER/jobseeker_dashboard.php');
                    exit;
                } else {
                    // Job-seeker not registered, proceed with registration
                    file_put_contents('debug_log.txt', "Redirecting to job-seeker registration.\n", FILE_APPEND);
                    header('Location: ../JOBSEEKER/jobseeker_registration.php');
                    exit;
                }
            } elseif ($row['role'] === "employer") {
                // Check if employer profile exists
                $employerQuery = "SELECT employer_id FROM employers WHERE user_id = :user_id";
                $stmtEmployer = $db->prepare($employerQuery); // Changed variable name
                $stmtEmployer->bindParam(':user_id', $row['user_id']);
                $stmtEmployer->execute();

                if ($stmtEmployer->rowCount() > 0) {
                    // Employer profile exists, redirect to dashboard
                    $_SESSION['employer_id'] = $stmtEmployer->fetch(PDO::FETCH_ASSOC)['employer_id'];
                    header('Location: ../EMPLOYER/employer_dashboard.php');
                    exit;
                } else {
                    // Employer not registered, proceed with registration
                    file_put_contents('debug_log.txt', "Redirecting to employer registration.\n", FILE_APPEND);
                    header('Location: ../EMPLOYER/employer_registration.php');
                    exit;
                }
            }
        }
    }

    if (!$isAuthenticated) {
        file_put_contents('debug_log.txt', "Invalid credentials for: $email\n", FILE_APPEND);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid email or password.',
                icon: 'error'
            }).then(() => {
                window.location.href = '../LOGIN/login.php';
            });
        </script>";
        exit; // Halt further script execution
    }
} else {
    file_put_contents('debug_log.txt', "No users found in the system.\n", FILE_APPEND);
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'Error!',
            text: 'No users found in the system.',
            icon: 'error'
        }).then(() => {
            window.location.href = '../LOGIN/login.php';
        });
    </script>";
    exit; // Halt further script execution
}
?>
