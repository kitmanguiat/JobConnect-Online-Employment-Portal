<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/employerCrud.php';

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../LOGIN/login.php");
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Database connection
$database = new Database();
$db = $database->getConnect();

// Create Employer object
$employer = new Employer($db);
$employer->user_id = $user_id;

// Fetch employer details
$employerDetails = $employer->getEmployerByUserId();

// Fetch job postings for this employer
$jobPostings = $employer->getJobPostings();

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
        <h1>JobConnect</h1>
        <nav class="section-navbar">
            <ul>
                <li><a href="../EMPLOYER/employer_dashboard.php">Dashboard</a></li>
                <li><a href="../EMPLOYER/employer_post_job.php">Post/Manage Job</a></li>
                <li><a href="../EMPLOYER/employer_view_applicants.php">View Applicants</a></li>
                <li><a href="../EMPLOYER/employer_company_profile.php">Company Profile</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <section class="profile">
            <h3>Company Profile</h3>
            <?php if ($employerDetails): ?>
                <div class="company-profile">
                    <!-- Displaying company logo if available -->
                    <?php if (!empty($employerDetails['logo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($employerDetails['logo']); ?>" alt="Company Logo" class="company-logo">
                    <?php else: ?>
                        <p>No logo available</p>
                    <?php endif; ?>

                    <p><strong>Company Name:</strong> <?php echo htmlspecialchars($employerDetails['company_name']); ?></p>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($employerDetails['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($employerDetails['location']); ?></p>
                    <p><strong>Company Size:</strong> <?php echo htmlspecialchars($employerDetails['company_size']); ?></p>
                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($employerDetails['contact_number']); ?></p>
                    <p><strong>Company Description:</strong> <?php echo htmlspecialchars($employerDetails['company_description']); ?></p>
                    <p><strong>Founded Year:</strong> <?php echo htmlspecialchars($employerDetails['founded_year']); ?></p>
                </div>
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
                <p>No job postings found. <a href="../EMPLOYER/employer_post_job.php">Post your first job</a>!</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 JobConnect. All rights reserved.</p>
    </footer>
</body>
</html>
