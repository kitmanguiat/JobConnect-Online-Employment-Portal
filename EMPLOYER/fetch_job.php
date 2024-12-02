<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

$database = new Database();
$db = $database->getConnect();

$jobPosting = new JobPosting($db);
$stmt = $jobPosting->read();
$jobs = [];

// Fetch all job details, including fields like requirements and posted_at
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $jobs[] = [
        'job_posting_id' => htmlspecialchars($row['job_posting_id']),
        'job_title' => htmlspecialchars($row['job_title']),
        'description' => htmlspecialchars($row['description']),
        'requirements' => htmlspecialchars($row['requirements']),
        'location' => htmlspecialchars($row['location']),
        'salary' => htmlspecialchars($row['salary']),
        'status' => htmlspecialchars($row['status']),
        'posted_at' => htmlspecialchars($row['posted_at']),
        // Include Action buttons (Delete and Edit)
        'actions' => '
            <button class="edit-btn" data-id="' . htmlspecialchars($row['job_posting_id']) . '">Edit</button>
            <button class="delete-btn" data-id="' . htmlspecialchars($row['job_posting_id']) . '">Delete</button>
        ',
    ];
}

echo json_encode($jobs);
?>
