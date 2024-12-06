<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/jobSeekerCrud.php';

// Get the database connection
$database = new Database();
$db = $database->getConnect();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Instantiate the JobSeeker class
$jobSeeker = new JobSeeker($db);

// Fetch job seeker details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM job_seekers WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$jobSeekerData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data is found
if (!$jobSeekerData) {
    echo "No job seeker data found.";
    exit;
}

// Handle profile update on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile data
    $jobSeeker->user_id = $user_id;
    $jobSeeker->full_name = htmlspecialchars(trim($_POST['full_name']));
    $jobSeeker->availability = htmlspecialchars(trim($_POST['availability']));
    $jobSeeker->location = htmlspecialchars(trim($_POST['location']));
    $jobSeeker->phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Handle resume upload
    $jobSeeker->resume = $jobSeekerData['resume']; // Default to current resume if not updated
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $resume_tmp = $_FILES['resume']['tmp_name'];
        $resume_name = $_FILES['resume']['name'];
        $resume_extension = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];

        if (in_array($resume_extension, $allowed_extensions)) {
            $resume_new_name = uniqid('', true) . '.' . $resume_extension;
            $resume_dir = '../uploads/resumes/';

            if (!file_exists($resume_dir)) {
                mkdir($resume_dir, 0777, true);
            }

            if (move_uploaded_file($resume_tmp, $resume_dir . $resume_new_name)) {
                $jobSeeker->resume = $resume_dir . $resume_new_name;
            } else {
                echo "Error uploading resume.";
                exit;
            }
        } else {
            echo "Invalid file type for resume.";
            exit;
        }
    }

    // Update the profile
    if ($jobSeeker->update()) {
        echo "Profile updated successfully!";
        header("Location: ../JOBSEEKER/jobseeker_dashboard.php"); // Redirect to dashboard after update
    } else {
        echo "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB SEEKER DASHBOARD</title>
    <link rel="stylesheet" href="../CSS/jobseeker_profile.css">
</head>

<body>
    <header>
        <h1>JOB SEEKER DASHBOARD</h1>
        <nav>
            <ul>
                <li><a href="../JOBSEEKER/jobseeker_dashboard.php">Dashboard</a></li>
                <li><a href="../JOBSEEKER/jobseeker_profile.php">Profile</a></li>
                <li><a href="../JOBSEEKER/jobseeker_viewjob.php">View Jobs</a></li>
                <li><a href="../JOBSEEKER/jobseeker_my_applications.php">View Application</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <h2>Edit Your Profile</h2>
    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($jobSeekerData['full_name']); ?>" required><br>

        <label for="availability">Availability:</label>
        <select name="availability">
            <option value="full-time" <?php echo ($jobSeekerData['availability'] == 'full-time') ? 'selected' : ''; ?>>Full-Time</option>
            <option value="part-time" <?php echo ($jobSeekerData['availability'] == 'part-time') ? 'selected' : ''; ?>>Part-Time</option>
            <option value="freelance" <?php echo ($jobSeekerData['availability'] == 'freelance') ? 'selected' : ''; ?>>Freelance</option>
        </select><br>

        <label for="location">Address:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($jobSeekerData['location']); ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($jobSeekerData['phone_number']); ?>" required><br>

        <label for="resume">Resume (PDF, DOC, DOCX):</label>
        <input type="file" name="resume"><br>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture"><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
