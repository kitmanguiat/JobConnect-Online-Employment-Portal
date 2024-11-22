<?php
require_once '../PHP/Database.php';
require_once '../PHP/JobSeeker.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job-seeker') {
    die("Access denied. You must be logged in as a job-seeker to register.");
}

if (isset($_POST['register_jobseeker'])) {
    $user_id = $_SESSION['user_id'];
    $resume = trim($_POST['resume']);
    $skills = trim($_POST['skills']);
    $experience = trim($_POST['experience']);
    $location = trim($_POST['location']);
    $phone_number = trim($_POST['phone_number']);
    $profile_picture_url = null;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/profile_pictures/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $profile_picture_url = $upload_dir . $user_id . "_" . $file_name;

        if (!move_uploaded_file($file_tmp, $profile_picture_url)) {
            die("Failed to upload profile picture.");
        }
    }

    // Register job-seeker using the JobSeeker class
    $database = new Database();
    $db = $database->getConnection();
    $jobSeeker = new JobSeeker($db);

    try {
        if ($jobSeeker->register($user_id, $resume, $skills, $experience, $location, $phone_number, $profile_picture_url)) {
            echo "Job-Seeker profile registered successfully!";
            header("Location: ../HTML/jobseeker_dashboard.html");
            exit;
        } else {
            echo "An error occurred during registration.";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>