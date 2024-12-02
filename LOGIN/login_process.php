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

            // Check if the user is a job-seeker or employer
            if ($row['role'] === "job-seeker") {
                // Check if job-seeker profile exists
                $jobSeekerCheckQuery = "SELECT job_seeker_id FROM job_seekers WHERE user_id = :user_id";
                $stmt = $db->prepare($jobSeekerCheckQuery);
                $stmt->bindParam(':user_id', $row['user_id']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Job-seeker profile exists, redirect to dashboard
                    $_SESSION['job_seeker_id'] = $stmt->fetch(PDO::FETCH_ASSOC)['job_seeker_id'];
                    header('Location: ../JOBSEEKER/jobseeker_dashboard.php');
                } else {
                    // Job-seeker not registered, proceed with registration
                    header('Location: ../JOBSEEKER/jobseeker_registration.php');
                }
            } elseif ($row['role'] === "employer") {
                // Check if employer profile exists
                $employerCheckQuery = "SELECT employer_id FROM employers WHERE user_id = :user_id";
                $stmt = $db->prepare($employerCheckQuery);
                $stmt->bindParam(':user_id', $row['user_id']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Employer profile exists, redirect to dashboard
                    $_SESSION['employer_id'] = $stmt->fetch(PDO::FETCH_ASSOC)['employer_id'];
                    header('Location: ../EMPLOYER/employer_dashboard.php');
                } else {
                    // Employer not registered, proceed with registration
                    header('Location: ../EMPLOYER/employer_registration.php');
                }
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
