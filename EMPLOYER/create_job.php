<?php
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

try {
    session_start(); // Start session to access employer_id

    // Establish database connection
    $database = new Database();
    $db = $database->getConnect();

    // Initialize JobPosting object
    $jobPosting = new JobPosting($db);

    // Set job posting properties (ensure proper validation)
    if (isset($_SESSION['employer_id'])) {
        $jobPosting->employer_id = $_SESSION['employer_id']; // Get the employer ID from the session
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Employer not logged in."]);
        exit;
    }

    // Set job posting details from the POST request
    $jobPosting->job_title = trim($_POST['job_title']);
    $jobPosting->description = trim($_POST['description']);
    $jobPosting->requirements = trim($_POST['requirements']);
    $jobPosting->location = trim($_POST['location']);
    $jobPosting->salary = floatval($_POST['salary']);
    $jobPosting->status = trim($_POST['status']);

    // Additional validation for critical fields
    if (empty($jobPosting->job_title) || empty($jobPosting->description) || empty($jobPosting->employer_id) ||
        empty($jobPosting->location) || empty($jobPosting->salary) || empty($jobPosting->status)) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Missing required fields."]);
        exit;
    }

    // Check if the employer exists in the database
    $validateEmployerQuery = "SELECT COUNT(*) FROM employers WHERE employer_id = :employer_id";
    $validateStmt = $db->prepare($validateEmployerQuery);
    $validateStmt->bindParam(':employer_id', $jobPosting->employer_id, PDO::PARAM_INT);
    $validateStmt->execute();

    if ($validateStmt->fetchColumn() == 0) {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Employer not found."]);
        exit;
    }

    // Attempt to create the job posting
    if ($jobPosting->create()) {
        http_response_code(201); // Created
        echo json_encode(["message" => "Job created successfully!"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["message" => "Failed to create job."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Unexpected error: " . $e->getMessage()]);
}
?>
