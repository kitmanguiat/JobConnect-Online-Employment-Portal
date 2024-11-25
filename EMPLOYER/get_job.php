<?php
require_once 'dbConnection.php';
require_once 'JobPosting.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$jobPosting->id = $_GET['id'];

$stmt = $jobPosting->read();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'id' => htmlspecialchars($row['id']),
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