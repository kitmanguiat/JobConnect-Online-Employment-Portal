<?php
session_start();
include 'config.php';

// Check credentials (assuming POST method)
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role']; // 'job-seeker' or 'employer'

    // Redirect based on role
    if ($user['role'] === 'job-seeker') {
        header("Location: job-seeker.html");
        exit();
    } elseif ($user['role'] === 'employer') {
        header("Location: employer.html");
        exit();
    }
} else {
    echo "Invalid credentials";
}
?>
