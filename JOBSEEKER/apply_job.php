<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

var_dump($_SESSION['user_id']); // Add this line to check session variable

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to apply for a job.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_posting_id = $_POST['job_posting_id'];
    $job_seeker_id = $_SESSION['user_id'];  // Get the user_id from the session, not POST

    $database = new Database();
    $db = $database->getConnect();

    try {
        // Check if the job seeker exists in the job_seekers table
        $checkUserQuery = "SELECT COUNT(*) FROM job_seekers WHERE job_seeker_id = :job_seeker_id";
        $stmt = $db->prepare($checkUserQuery);
        $stmt->bindParam(':job_seeker_id', $job_seeker_id);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            echo "You are not a valid job seeker. Please check your profile or contact support.";
            exit;
        }

        // Check if the user has already applied for this job
        $checkUserQuery = "SELECT COUNT(*) FROM job_seekers WHERE job_seeker_id = :job_seeker_id";
        $stmt = $db->prepare($checkUserQuery);
        $stmt->bindParam(':job_seeker_id', $job_seeker_id);
        
        try {
            $stmt->execute();
            $count = $stmt->fetchColumn();
            var_dump($count);  // Debug the result
            if ($count == 0) {
                echo "You are not a valid job seeker.";
                exit;
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
        

        if ($stmt->fetchColumn() > 0) {
            echo "You have already applied for this job.";
        } else {
            // Insert the new application
            $insertQuery = "INSERT INTO applications (job_posting_id, job_seeker_id, application_date, status) 
                            VALUES (:job_posting_id, :job_seeker_id, NOW(), 'pending')";
            $stmt = $db->prepare($insertQuery);
            $stmt->bindParam(':job_posting_id', $job_posting_id);
            $stmt->bindParam(':job_seeker_id', $job_seeker_id);

            if ($stmt->execute()) {
                echo "success";  // Respond with success message
            } else {
                echo "Failed to apply for the job. Please try again.";
            }
        }
    } catch (PDOException $e) {
        // Handle any errors that occur during the query
        echo "Error: " . $e->getMessage();
    }
}
?>