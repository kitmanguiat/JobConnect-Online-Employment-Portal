<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$jobPosting->job_posting_id = $_GET['job_posting_id'];

$stmt = $jobPosting->read();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'job_posting_id' => htmlspecialchars($row['job_posting_id']),
        'job_title' => htmlspecialchars($row['job_title']),
        'description' => htmlspecialchars($row['description']),
        'requirements' => htmlspecialchars($row['requirements']),
        'location' => htmlspecialchars($row['location']),
        'salary' => htmlspecialchars($row['salary']),
        'status' => htmlspecialchars($row['status'])
    ]);
} else {
    http_response_code(404);
    echo "Job not found.";
}
?>