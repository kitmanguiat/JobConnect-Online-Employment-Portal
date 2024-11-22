<?php
session_start(); 
require_once '../PHP/Database.php';
require_once '../PHP/Employer.php';

// Debugging line to check session data
var_dump($_SESSION);  // This will output all session variables. Look for 'user_id'

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit;  // Exit if the user is not logged in
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

// Check if a logo is uploaded
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $logo = $upload_dir . basename($_FILES['logo']['name']);
    
    // Attempt to move the uploaded logo file
    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $logo)) {
        echo "Failed to upload logo.";
        exit;  // Exit if logo upload fails
    }
} else {
    echo "No logo uploaded or an error occurred with the logo upload.";
    exit;  // Exit if no logo was uploaded
}

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Insert into the employers table
$query = "INSERT INTO employers (user_id, company_name, industry, company_description, company_size, location, founded_year, logo, contact_number)
          VALUES (:user_id, :company_name, :industry, :company_description, :company_size, :location, :founded_year, :logo, :contact_number)";

$stmt = $db->prepare($query);

// Bind parameters to the query
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
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Success!',
            text: 'Successfully Registered',
            icon: 'success',
            confirmButtonText: 'Okay'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../HTML/employer_dashboard.html';
            }
        });
        </script>
        </body>
        </html>";
} else {
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Incorrect password or email. Try again!',
            icon: 'error',
            confirmButtonText: 'Okay'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../HTML/employer_registration.html';
            }
        });
        </script>
        </body>
        </html>";
}
?>