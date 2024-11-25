<?php
require_once '../DATABASE/dbConnection.php';

$database = new Database();
$db = $database->getConnect();

$jobPostingId = htmlspecialchars($_GET['job_posting_id']);

// Query to fetch applicants
$query = "
    SELECT 
        a.application_id,
        js.name AS job_seeker_name,
        a.application_date,
        a.status
    FROM applications a
    JOIN job_seekers js ON a.job_seeker_id = js.id
    WHERE a.job_posting_id = :job_posting_id
";

$stmt = $db->prepare($query);
$stmt->bindParam(':job_posting_id', $jobPostingId);
$stmt->execute();

$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($applicants);
