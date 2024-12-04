<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/jobCrud.php';

try {
    // Establish database connection
    $database = new Database();
    $db = $database->getConnect();

    // Initialize JobPosting object
    $jobPosting = new JobPosting($db);

    // Validate employer session
    if (isset($_SESSION['employer_id'])) {
        $jobPosting->employer_id = $_SESSION['employer_id'];
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Employer not logged in."]);
        exit;
    }

    // Validate and sanitize inputs
    $requiredFields = ['job_title', 'description', 'requirements', 'location', 'salary', 'status'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Missing required field: $field."]);
            exit;
        }
    }

    // Set job posting details
    $jobPosting->job_title = htmlspecialchars(trim($_POST['job_title']));
    $jobPosting->description = htmlspecialchars(trim($_POST['description']));
    $jobPosting->requirements = htmlspecialchars(trim($_POST['requirements']));
    $jobPosting->location = htmlspecialchars(trim($_POST['location']));
    $jobPosting->salary = floatval($_POST['salary']);
    $jobPosting->status = htmlspecialchars(trim($_POST['status']));

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