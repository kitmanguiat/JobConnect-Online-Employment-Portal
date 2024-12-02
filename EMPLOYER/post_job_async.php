<?php
session_start();
header('Content-Type: application/json');
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobPostingCrud.php';

$response = ["success" => false, "message" => "Unknown error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if employer is logged in
    if (!isset($_SESSION['user_id'])) {
        $response["message"] = "You must be logged in to post a job.";
        echo json_encode($response);
        exit;
    }

    // Get database connection
    $database = new Database();
    $db = $database->getConnect();

    // Instantiate JobPosting class
    $jobPosting = new JobPosting($db);

    $jobPosting->employer_id = $_SESSION['user_id']; // Assume employer ID is stored in session
    $jobPosting->job_title = htmlspecialchars(trim($_POST['job_title']));
    $jobPosting->description = htmlspecialchars(trim($_POST['description']));
    $jobPosting->requirements = htmlspecialchars(trim($_POST['requirements']));
    $jobPosting->location = htmlspecialchars(trim($_POST['location']));
    $jobPosting->salary = htmlspecialchars(trim($_POST['salary']));
    $jobPosting->status = htmlspecialchars(trim($_POST['status']));

    if ($jobPosting->create()) {
        $response["success"] = true;
        $response["message"] = "Job posted successfully!";
    } else {
        $response["message"] = "Failed to post job.";
    }
}

echo json_encode($response);
