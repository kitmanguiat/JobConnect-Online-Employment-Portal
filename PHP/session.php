<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit;
} else {
    $user_id = $_SESSION['user_id'];  // Get the user ID from the session
    echo "User ID: " . $user_id;  // For debugging purposes
}
?>
