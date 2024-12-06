<?php
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

$database = new Database();
$db = $database->getConnect();

// Ensure required POST fields are set
if (isset($_POST['application_id'], $_POST['status'])) {
    $applicationId = htmlspecialchars($_POST['application_id']);
    $status = htmlspecialchars($_POST['status']);

    $jobApplication = new JobApplication($db);
    $jobApplication->application_id = $applicationId;
    $jobApplication->status = $status;

    // Update the application status
    if ($jobApplication->updateApplicationStatus()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error: Missing required fields";
}

?>