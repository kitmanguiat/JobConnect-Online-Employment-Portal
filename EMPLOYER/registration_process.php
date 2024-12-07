<?php
session_start(); // Ensure session_start is the very first line
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
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'User not logged in. Please log in and try again.',
                icon: 'error'
            }).then(() => {
                window.location.href = '../LOGIN/login.php'; // Redirect to login
            });
        </script>";
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
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error uploading logo. Please try again.',
                        icon: 'error'
                    }).then(() => {
                        window.history.back(); // Return to the form
                    });
                </script>";
                exit;
            }
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Invalid File!',
                    text: 'Only JPG, JPEG, PNG, or GIF files are allowed.',
                    icon: 'error'
                }).then(() => {
                    window.history.back(); // Return to the form
                });
            </script>";
            exit;
        }
    } else {
        $employer->logo = null; // Logo is optional
    }

    // Attempt to create the employer in the database
    if ($employer->create()) {
        // Retrieve the last inserted employer ID
        $employer_id = $db->lastInsertId();

        // Store employer ID in the session
        $_SESSION['employer_id'] = $employer_id;

        // Success - redirect using PHP header()
        header("Location: ../EMPLOYER/employer_dashboard.php");
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Registration failed. Please check your details and try again.',
                icon: 'error'
            }).then(() => {
                window.history.back(); // Return to the form
            });
        </script>";
    }
}
?>
