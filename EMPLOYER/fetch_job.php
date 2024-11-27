<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$stmt = $jobPosting->read();
$jobs = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $jobs[] = [
        htmlspecialchars($row['id']),
        htmlspecialchars($row['job_title']),
        htmlspecialchars($row['description']),
        htmlspecialchars($row['location']),
        htmlspecialchars($row['salary']),
        htmlspecialchars($row['status']),
        '<button class="delete-btn" data-id="' . htmlspecialchars($row['id']) . '">Delete</button>'
    ];
}

echo json_encode($jobs);
?>