<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);

// Validate and set all required fields for the update
$jobPosting->job_posting_id = $_POST['job_posting_id'] ?? null;
$jobPosting->job_title = $_POST['job_title'] ?? null;
$jobPosting->description = $_POST['description'] ?? null;
$jobPosting->requirements = $_POST['requirements'] ?? null;
$jobPosting->location = $_POST['location'] ?? null;
$jobPosting->salary = $_POST['salary'] ?? null;
$jobPosting->status = $_POST['status'] ?? null;

// Ensure all mandatory fields are present
if (empty($jobPosting->job_posting_id) || empty($jobPosting->job_title) || empty($jobPosting->status)) {
    http_response_code(400); // Bad request
    echo "Missing required fields.";
    exit();
}

// Attempt to update the job posting
if ($jobPosting->update()) {
    echo "Job updated successfully!";
} else {
    http_response_code(500); // Internal server error
    echo "Failed to update job.";
}
?>
