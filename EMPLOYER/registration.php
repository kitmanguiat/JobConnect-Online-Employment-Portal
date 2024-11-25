<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/employerCrud.php'; // Path to Employer.php

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the database connection
    $database = new Database();
    $db = $database->getConnect();

    // Create an Employer object
    $employer = new Employer($db);

    // Ensure user is logged in and retrieve the user ID from the session
    if (isset($_SESSION['user_id'])) {
        $employer->user_id = $_SESSION['user_id']; // Assign user_id from session
    } else {
        echo "Error: User not logged in.";
        exit;
    }

    // Collect form data
    $employer->company_name = htmlspecialchars(trim($_POST['company_name']));
    $employer->industry = htmlspecialchars(trim($_POST['industry']));
    $employer->company_description = htmlspecialchars(trim($_POST['company_description']));
    $employer->company_size = htmlspecialchars(trim($_POST['company_size']));
    $employer->location = htmlspecialchars(trim($_POST['location']));
    $employer->founded_year = htmlspecialchars(trim($_POST['founded_year']));
    $employer->contact_number = htmlspecialchars(trim($_POST['contact_number']));

    // Handle file upload for the company logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        // File upload logic here (as described in the previous example)
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_name = $_FILES['logo']['name'];
        $logo_extension = strtolower(pathinfo($logo_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($logo_extension, $allowed_extensions)) {
            $logo_new_name = uniqid('', true) . '.' . $logo_extension;
            $logo_dir = '../uploads/logos/';

            if (!file_exists($logo_dir)) {
                mkdir($logo_dir, 0777, true);
            }

            if (move_uploaded_file($logo_tmp, $logo_dir . $logo_new_name)) {
                $employer->logo = $logo_dir . $logo_new_name;
            } else {
                echo "Error uploading logo.";
                exit;
            }   
        } else {
            echo "Invalid file type.";
            exit;
        }
    } else {
        $employer->logo = null;
    }

    // Attempt to create the employer in the database
    if ($employer->create()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Employer registration successful!',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../EMPLOYER/employer_dashboard.php';
                }
            });
        </script>";
    } else {
        echo "Error during registration.";
    }
}
?>
