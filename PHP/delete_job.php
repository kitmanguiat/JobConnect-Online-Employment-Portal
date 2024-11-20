<?php
require_once "../PHP/main_DB.php";
require_once "../PHP/employer_manage_job.php";
$jobManager = new JobManager('localhost', 'dbjobconnect', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $jobId = (int)$_POST['job_id'];
    if ($jobManager->deleteJob($jobId)) {
        header("Location: ../PHP/employer_manage_job.php?status=success");
    } else {
        echo "Error deleting job.";
    }
}
?>
