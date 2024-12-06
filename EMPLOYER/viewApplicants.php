<?php
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

$database = new Database();
$db = $database->getConnect();
$applicationManager = new ApplicationManager();

// Ensure the job_posting_id is passed and is valid
if (isset($_GET['job_posting_id']) && is_numeric($_GET['job_posting_id'])) {
    $jobPostingId = htmlspecialchars($_GET['job_posting_id']);

    try {
        // Fetch applicants using the centralized method
        $applicants = $applicationManager->getApplicantsByJobPosting($jobPostingId);

        if (!empty($applicants)) {
            echo json_encode($applicants);
        } else {
            error_log("No applicants found for job posting ID $jobPostingId");
            echo json_encode([]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    error_log('Invalid or missing job posting ID in the URL');
    echo json_encode(["error" => "Invalid job posting ID"]);
}

?>