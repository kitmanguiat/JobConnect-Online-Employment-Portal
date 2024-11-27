<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$jobPosting->id = $_POST['id'];

if ($jobPosting->delete()) {
    echo "Job deleted successfully!";
} else {
    http_response_code(500);
    echo "Failed to delete job.";
}
?>