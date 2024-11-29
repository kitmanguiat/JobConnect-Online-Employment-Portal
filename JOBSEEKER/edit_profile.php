<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/jobSeekerCrud.php';

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

// Fetch job seeker details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM job_seekers WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$jobSeekerData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data is found
if (!$jobSeekerData) {
    echo "No job seeker data found.";
    exit;
}

// Handle profile update on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile data
    $jobSeeker->user_id = $user_id;
    $jobSeeker->full_name = htmlspecialchars(trim($_POST['full_name']));
    $jobSeeker->availability = htmlspecialchars(trim($_POST['availability']));
    $jobSeeker->location = htmlspecialchars(trim($_POST['location']));
    $jobSeeker->phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Handle resume upload
    $jobSeeker->resume = $jobSeekerData['resume']; // Default to current resume if not updated
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

    // Update the profile
    if ($jobSeeker->update()) {
        echo "Profile updated successfully!";
        header("Location: ../JOBSEEKER/jobseeker_dashboard.php"); // Redirect to dashboard after update
    } else {
        echo "Failed to update profile.";
    }
}
?>