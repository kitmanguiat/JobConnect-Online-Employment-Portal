<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_posting_id = $_POST['job_posting_id'];
    $user_id = $_SESSION['user_id']; // Using user_id from the session

    $database = new Database();
    $db = $database->getConnect();

    // Check if the user is a valid job seeker
    $checkUserQuery = "SELECT COUNT(*) FROM job_seekers WHERE user_id = :user_id";
    $stmt = $db->prepare($checkUserQuery);
    $stmt->bindParam(':user_id', $user_id); // Use user_id to validate job seeker

    try {
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            echo "You are not a valid job seeker. Please check your profile or contact support.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }

    // Check if the user has already applied for the job
    $checkApplicationQuery = "SELECT COUNT(*) FROM applications WHERE job_posting_id = :job_posting_id AND job_seeker_id = (SELECT job_seeker_id FROM job_seekers WHERE user_id = :user_id)";
    $stmt = $db->prepare($checkApplicationQuery);
    $stmt->bindParam(':job_posting_id', $job_posting_id);
    $stmt->bindParam(':user_id', $user_id);

    try {
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            echo "You have already applied for this job.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }

    // Insert the new application
    $insertQuery = "INSERT INTO applications (job_posting_id, job_seeker_id, application_date, status)
                    VALUES (:job_posting_id, 
                            (SELECT job_seeker_id FROM job_seekers WHERE user_id = :user_id), 
                            NOW(), 
                            'pending')";
    $stmt = $db->prepare($insertQuery);
    $stmt->bindParam(':job_posting_id', $job_posting_id);
    $stmt->bindParam(':user_id', $user_id);

    try {
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Failed to apply for the job. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>