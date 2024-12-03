<?php
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

$database = new Database();
$db = $database->getConnect();

// Fetch jobs
$query = "SELECT * FROM job_postings WHERE status = 'open'";
$stmt = $db->prepare($query);
$stmt->execute();

$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jobs);
?>
