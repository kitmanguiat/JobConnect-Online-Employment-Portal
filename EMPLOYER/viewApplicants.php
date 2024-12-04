<?php
require_once '../DATABASE/dbConnection.php';

$database = new Database();
$db = $database->getConnect();

// Ensure the job_posting_id is passed and is valid
if (isset($_GET['job_posting_id']) && is_numeric($_GET['job_posting_id'])) {
    $jobPostingId = htmlspecialchars($_GET['job_posting_id']); // Sanitize input
    
    // Log job_posting_id for debugging
    error_log('Received job_posting_id: ' . $jobPostingId); // Check if job_posting_id is correctly passed

    // Query to fetch applicants for the given job posting
    $query = "
        SELECT 
            a.application_id,
            js.full_name AS job_seeker_name,
            a.application_date,
            a.status
        FROM applications a
        JOIN job_seekers js ON a.job_seeker_id = js.job_seeker_id
        WHERE a.job_posting_id = :job_posting_id
    ";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':job_posting_id', $jobPostingId, PDO::PARAM_INT); // Bind as integer for safety
        $stmt->execute();

        // Check if any applicants are found
        if ($stmt->rowCount() > 0) {
            // Log number of applicants found
            error_log('Number of applicants: ' . $stmt->rowCount()); // Log the number of applicants found

            // Fetch the results as an associative array
            $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($applicants);
        } else {
            // If no applicants found, log and return an empty array
            error_log('No applicants found for job posting ID ' . $jobPostingId); // Log if no applicants found
            echo json_encode([]);
        }
    } catch (PDOException $e) {
        // Log any database errors
        error_log('Database Error: ' . $e->getMessage());
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Log if job_posting_id is not provided or invalid
    error_log('No job posting ID provided in the URL');
    echo json_encode(["error" => "Invalid job posting ID"]);
}
?>
