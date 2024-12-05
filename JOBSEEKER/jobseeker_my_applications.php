<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Fetch the job seeker's ID
$user_id = $_SESSION['user_id'];

// Initialize database connection
$database = new Database();
$db = $database->getConnect();

// Initialize job application class
$jobApplication = new JobApplication($db);
$jobApplication->job_seeker_id = $user_id;

// Fetch the job applications
$stmt = $jobApplication->fetchByJobSeeker();

// Debugging step 1: Check query execution
if (!$stmt) {
    echo "Error fetching data: ";
    print_r($db->errorInfo());
    exit;
}

// Debugging step 2: Output raw results
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($applications)) {
    echo "No data found for this job seeker.";
    // Optionally, remove the `exit;` if you want to display a proper message in HTML instead of exiting.
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Applications</h1>
        <nav>
            <ul>
                <li><a href="../JOBSEEKER/jobseeker_dashboard.php">Dashboard</a></li>
                <li><a href="../JOBSEEKER/jobseeker_profile.php">Profile</a></li>
                <li><a href="../JOBSEEKER/jobseeker_view_job.php">View Jobs</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Your Applications</h2>
        <table id="applicationsTable">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Requirements</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?= htmlspecialchars($application['job_title']); ?></td>
                        <td><?= htmlspecialchars($application['description']); ?></td>
                        <td><?= htmlspecialchars($application['requirements']); ?></td>
                        <td><?= htmlspecialchars($application['location']); ?></td>
                        <td><?= htmlspecialchars($application['salary']); ?></td>
                        <td><?= htmlspecialchars($application['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable();
        });
    </script>
</body>
</html>
