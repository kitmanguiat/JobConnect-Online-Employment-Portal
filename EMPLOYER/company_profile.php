<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/employerCrud.php';

$database = new Database();
$db = $database->getConnect();
$employer = new Employer($db);

// Check if the employer is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch employer details (for the form's initial values)
$query = "SELECT * FROM employers WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$employer_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employer->user_id = $user_id;
    $employer->company_name = htmlspecialchars(trim($_POST['company_name']));
    $employer->industry = htmlspecialchars(trim($_POST['industry']));
    $employer->company_description = htmlspecialchars(trim($_POST['company_description']));
    $employer->company_size = htmlspecialchars(trim($_POST['company_size']));
    $employer->location = htmlspecialchars(trim($_POST['location']));
    $employer->founded_year = htmlspecialchars(trim($_POST['founded_year']));
    $employer->contact_number = htmlspecialchars(trim($_POST['contact_number']));

    // Handle logo upload
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
                echo "Error uploading logo.";
                exit;
            }
        } else {
            echo "Invalid file type.";
            exit;
        }
    } else {
        $employer->logo = $employer_data['logo']; // Keep existing logo
    }

    // Update the employer profile
    if ($employer->update()) {
        // Refresh employer data
        $stmt = $db->prepare("SELECT * FROM employers WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $employer_data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "Profile updated successfully!";
        header("Location: ../EMPLOYER/employer_company_profile.php");
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Failed to update profile: " . $errorInfo[2];
    }
}
?>