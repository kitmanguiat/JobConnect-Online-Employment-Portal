<?php

require_once '../PHP/Database.php';
require_once '../PHP/Employer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}  // Ensure session is started

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit;
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Collect form data
$company_name = trim($_POST['company_name']);
$industry = trim($_POST['industry']);
$company_description = trim($_POST['company_description']);
$company_size = $_POST['company_size'];
$location = trim($_POST['location']);
$founded_year = $_POST['founded_year'];
$contact_number = trim($_POST['contact_number']);

// Handle logo upload
$logo = null;
$upload_dir = "../uploads/logos/";

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $logo = $upload_dir . basename($_FILES['logo']['name']);
    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $logo)) {
        echo "Failed to upload logo.";
        exit;
    }
}

// Create a database connection
require_once '../PHP/Database.php';
$database = new Database();
$db = $database->getConnection();

// Insert into the employers table
$query = "INSERT INTO employers (user_id, company_name, industry, company_description, company_size, location, founded_year, logo, contact_number)
          VALUES (:user_id, :company_name, :industry, :company_description, :company_size, :location, :founded_year, :logo, :contact_number)";

$stmt = $db->prepare($query);

// Bind parameters
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':company_name', $company_name);
$stmt->bindParam(':industry', $industry);
$stmt->bindParam(':company_description', $company_description);
$stmt->bindParam(':company_size', $company_size);
$stmt->bindParam(':location', $location);
$stmt->bindParam(':founded_year', $founded_year);
$stmt->bindParam(':logo', $logo);
$stmt->bindParam(':contact_number', $contact_number);

// Execute the query
if ($stmt->execute()) {
    echo "Employer registration successful!";
    header("Location: ../HTML/employer_dashboard.html");  // Redirect to the dashboard
    exit;
} else {
    echo "An error occurred during employer registration.";
}
?>
