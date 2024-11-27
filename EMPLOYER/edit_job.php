<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$jobPosting->id = $_POST['id'];
$jobPosting->job_title = $_POST['job_title'];
$jobPosting->description = $_POST['description'];
$jobPosting->requirements = $_POST['requirements'];
$jobPosting->location = $_POST['location'];
$jobPosting->salary = $_POST['salary'];
$jobPosting->status = $_POST['status'];

if ($jobPosting->update()) {
    echo "Job updated successfully!";
} else {
    http_response_code(500);
    echo "Failed to update job.";
}
?>