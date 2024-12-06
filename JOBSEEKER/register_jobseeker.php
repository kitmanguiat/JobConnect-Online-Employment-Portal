<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/jobSeekerCrud.php'; // Include the JobSeeker class

// Get the database connection
$database = new Database();
$db = $database->getConnect();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Instantiate the JobSeeker class
$jobSeeker = new JobSeeker($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming user_id comes from a session after login
    $user_id = $_SESSION['user_id'];

    // Get the job seeker data from POST
    $jobSeeker->user_id = $user_id;
    $jobSeeker->full_name = htmlspecialchars(trim($_POST['full_name']));
    $jobSeeker->availability = htmlspecialchars(trim($_POST['availability']));
    $jobSeeker->location = htmlspecialchars(trim($_POST['location']));
    $jobSeeker->phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Handle resume upload
    $jobSeeker->resume = null; // Default to null if no resume is uploaded
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $resume_tmp = $_FILES['resume']['tmp_name'];
        $resume_name = $_FILES['resume']['name'];
        $resume_extension = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];

        if (in_array($resume_extension, $allowed_extensions)) {
            $resume_new_name = uniqid('', true) . '.' . $resume_extension;
            $resume_dir = '../uploads/resumes/';

            if (!file_exists($resume_dir)) {
                mkdir($resume_dir, 0777, true);
            }

            if (move_uploaded_file($resume_tmp, $resume_dir . $resume_new_name)) {
                $jobSeeker->resume = $resume_dir . $resume_new_name;
            } else {
                echo "Error uploading resume.";
                exit;
            }
        } else {
            echo "Invalid file type for resume.";
            exit;
        }
    }

    // Handle profile picture upload (optional)
    $jobSeeker->profile_picture = null; // Default to null if no picture is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $profile_tmp = $_FILES['profile_picture']['tmp_name'];
        $profile_name = $_FILES['profile_picture']['name'];
        $profile_extension = strtolower(pathinfo($profile_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($profile_extension, $allowed_extensions)) {
            $profile_new_name = uniqid('', true) . '.' . $profile_extension;
            $profile_dir = '../uploads/profile_pictures/';

            if (!file_exists($profile_dir)) {
                mkdir($profile_dir, 0777, true);
            }

            if (move_uploaded_file($profile_tmp, $profile_dir . $profile_new_name)) {
                $jobSeeker->profile_picture = $profile_dir . $profile_new_name;
            } else {
                echo "Error uploading profile picture.";
                exit;
            }
        } else {
            echo "Invalid file type for profile picture.";
            exit;
        }
    }

    // Call create method to register job seeker
    if ($jobSeeker->create()) {
        // Redirect to landing page on successful registration
        header("Location: ../JOBSEEKER/jobseeker_dashboard.php");
        exit;
    } else {
        echo "Failed to register job seeker.";
    }
}
?>
