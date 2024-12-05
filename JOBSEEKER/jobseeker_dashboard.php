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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
 
</head>
<body>
    <header>
        <h1>Job Seeker Dashboard</h1>
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

    <section>
        <h2>Profile Overview</h2>
        <img src="<?php echo $jobSeekerData['profile_picture']; ?>" alt="Profile Picture" style="max-width: 150px;">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($jobSeekerData['full_name']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($jobSeekerData['location']); ?></p>
        <p><strong>Availability:</strong> <?php echo htmlspecialchars($jobSeekerData['availability']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($jobSeekerData['phone_number']); ?></p>

        <!-- Optionally include the Resume if it's uploaded -->
        <p><strong>Resume:</strong> 
            <?php if ($jobSeekerData['resume']) : ?>
                <a href="<?php echo htmlspecialchars($jobSeekerData['resume']); ?>" target="_blank">View Resume</a>
            <?php else : ?>
                No resume uploaded.
            <?php endif; ?>
        </p>

        <a href="../JOBSEEKER/jobseeker_profile.php">Edit Profile</a>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> JobConnect - All rights reserved.</p>
    </footer>
</body>
</html>
