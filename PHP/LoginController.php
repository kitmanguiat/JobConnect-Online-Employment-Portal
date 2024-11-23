<?php
require_once '../PHP/Database.php';
require_once '../PHP/User.php';
session_start();  // Start the session

// Check if the user is already logged in (session check)
if (isset($_SESSION['user_id'])) {
    header("Location: ../HTML/employer_registration.html");  // Redirect to dashboard if already logged in
    exit;
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
            exit;
        } elseif ($loggedInUser['role'] === 'employer') {
            // Check if employer profile exists
            $stmt = $db->prepare("SELECT * FROM employers WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Redirect to employer dashboard if profile is complete
                header("Location: ../HTML/employer_dashboard.html");
                exit;
            } else {
                // Redirect to employer registration if profile is not complete
                header("Location: ../HTML/employer_registration.html");
                exit;
            }
        }
    } else {
        echo "Invalid email or password.";
    }
}
?>
