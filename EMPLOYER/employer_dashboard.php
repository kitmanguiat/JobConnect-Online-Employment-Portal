<?php
session_start();
require_once '../DATABASE/dbConnection.php';

// Check if the user is logged in and is an employer
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
//     header("Location: ../HTML/main_login.html");
//     exit;
// }

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Database connection
$database = new Database();
$db = $database->getConnect();

// Fetch employer details
$employerQuery = "SELECT * FROM employers WHERE user_id = :user_id";
$employerStmt = $db->prepare($employerQuery);
$employerStmt->bindParam(':user_id', $user_id);
$employerStmt->execute();
$employer = $employerStmt->fetch(PDO::FETCH_ASSOC);

// Fetch job postings for this employer
$jobQuery = "SELECT * FROM job_postings WHERE employer_id = :employer_id";
$jobStmt = $db->prepare($jobQuery);
$jobStmt->bindParam(':employer_id', $employer['employer_id']);
$jobStmt->execute();
$jobPostings = $jobStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="../CSS/employer_dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <nav class="section-navbar">
            <ul>
                <li><a href="../PHP/employer_post_job.php">Post a Job</a></li>
                <li><a href="../PHP/employer_manage_job.php">Manage Job Listings</a></li>
                <li><a href="../PHP/employer_view_applicants.php">View Applicants</a></li>
                <li><a href="../PHP/employer_company_profile.php">Company Profile</a></li>
                <li><a href="../EMPLOYER/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="profile">
            <h2>Company Profile</h2>
            <?php if ($employer): ?>
                <p><strong>Company Name:</strong> <?php echo htmlspecialchars($employer['company_name']); ?></p>
                <p><strong>Industry:</strong> <?php echo htmlspecialchars($employer['industry']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($employer['location']); ?></p>
                <p><strong>Company Size:</strong> <?php echo htmlspecialchars($employer['company_size']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($employer['contact_number']); ?></p>
            <?php else: ?>
                <p>No employer profile found. Please complete your profile.</p>
            <?php endif; ?>
        </section>

        <section class="jobs">
            <h2>Your Job Postings</h2>
            <?php if ($jobPostings): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Posted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobPostings as $job): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($job['description'], 0, 50)) . '...'; ?></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><?php echo htmlspecialchars($job['status']); ?></td>
                                <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No job postings found. <a href="../PHP/employer_post_job.php">Post your first job</a>!</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 JobConnect. All rights reserved.</p>
    </footer>
</body>
</html>
