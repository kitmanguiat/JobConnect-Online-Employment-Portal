<?php
require_once 'dbConnection.php';
require_once 'JobPosting.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$jobPosting->employer_id = 1; // Replace with logged-in employer ID
$jobPosting->job_title = $_POST['job_title'];
$jobPosting->description = $_POST['description'];
$jobPosting->requirements = $_POST['requirements'];
$jobPosting->location = $_POST['location'];
$jobPosting->salary = $_POST['salary'];
$jobPosting->status = $_POST['status'];

if ($jobPosting->create()) {
    echo "Job created successfully!";
} else {
    http_response_code(500);
    echo "Failed to create job.";
}
?>