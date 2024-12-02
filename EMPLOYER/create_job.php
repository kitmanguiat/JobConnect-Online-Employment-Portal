<?php
require_once '../DATABASE/dbConnection.php';

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['employer_id'])) {
        error_log("Session error: Employer ID not set.");
        http_response_code(400);
        echo json_encode(["message" => "Employer not logged in."]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnect();

    $employer_id = $_SESSION['employer_id'];
    $job_title = trim($_POST['job_title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if (
        empty($job_title) || empty($description) || empty($requirements) ||
        empty($location) || $salary <= 0 || empty($status)
    ) {
        error_log("Validation error: Missing required fields.");
        http_response_code(400);
        echo json_encode(["message" => "All fields are required and salary must be a positive number."]);
        exit;
    }

    $query = "INSERT INTO job_postings (employer_id, job_title, description, requirements, location, salary, status)
              VALUES (:employer_id, :job_title, :description, :requirements, :location, :salary, :status)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':employer_id', $employer_id);
    $stmt->bindParam(':job_title', $job_title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':requirements', $requirements);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':salary', $salary);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Job created successfully!"]);
    } else {
        error_log("SQL error: " . print_r($stmt->errorInfo(), true));
        http_response_code(500);
        echo json_encode(["message" => "Failed to create job."]);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Unexpected error: " . $e->getMessage()]);
}
