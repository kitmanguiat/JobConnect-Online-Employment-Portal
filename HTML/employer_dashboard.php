<?php
session_start();

// Check if the employer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../PHP/login.php");
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/JobPosting.php';

// Initialize database connection and JobPosting class
$database = new Database();
$db = $database->getConnection();

// Fetch company and job data
$employer_id = $_SESSION['user_id'];
$companyInfo = $jobPosting->getEmployerInfo($employer_id); // Fetch employer/company info
$jobPostings = $jobPosting->getJobPostingsByEmployer($employer_id); // Fetch job postings

$companyName = $companyInfo['company_name'] ?? "Your Company";
$employerName = $_SESSION['username']; // Username of the logged-in employer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard | JobConnect</title>
    <link rel="stylesheet" href="../CSS/employer.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">JobConnect</div>
        <ul class="nav-links">
            <li><a href="../PHP/employer_dashboard.php" class="current">Dashboard</a></li>
            <li><a href="../PHP/employer_profile.php">Profile</a></li>
            <li><a href="../PHP/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="main-content">
            <header>
                <h1>Hello, <?php echo htmlspecialchars($companyName); ?></h1>
            </header>

            <nav class="section-navbar">
                <ul>
                    <li><a href="../PHP/employer_post_job.php">Post a Job</a></li>
                    <li><a href="../PHP/employer_manage_job.php">Manage Job Listings</a></li>
                    <li><a href="../PHP/employer_view_applicants.php">View Applicants</a></li>
                    <li><a href="../PHP/employer_company_profile.php">Company Profile</a></li>
                </ul>
            </nav>

            <div class="search-section">
                <input type="text" placeholder="Search by position title or work location">
                <button class="search-button">Search</button>
                <a href="../PHP/employer_post_job.php" class="post-job">Post a Job Vacancy</a>
            </div>

            <section id="job-postings">
                <h2>Job Postings</h2>
                <?php if (count($jobPostings) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Location</th>
                                <th>Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobPostings as $job): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($job['location']); ?></td>
                                    <td><?php echo htmlspecialchars($job['salary']); ?></td>
                                    <td><?php echo htmlspecialchars($job['status']); ?></td>
                                    <td>
                                        <a href="../PHP/edit_job_posting.php?id=<?php echo $job['id']; ?>">Edit</a> |
                                        <a href="../PHP/delete_job_posting.php?id=<?php echo $job['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this job posting?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You have no job postings. Create one now!</p>
                <?php endif; ?>
            </section>
        </div>

        <aside class="company-info">
            <h3>Company Info</h3>
            <img src="<?php echo htmlspecialchars($companyInfo['logo_url'] ?? 'logo-placeholder.png'); ?>" alt="Company Logo" class="company-logo">
            <p><a href="../PHP/employer_update_logo.php">Change logo</a></p>
            <p><strong>Employer Name:</strong> <?php echo htmlspecialchars($employerName); ?></p>
            <p><strong>Accreditation Status:</strong> <span class="status red">Verify accreditation</span></p>
            <p><a href="../PHP/employer_accreditation.php">Manage accreditation</a></p>
            <p><strong>Public Profile Link:</strong> <a href="../PHP/employer_company_profile.php">View company profile</a></p>
        </aside>
    </div>

    <footer>
        <p>&copy; 2024 JobConnect. All Rights Reserved.</p>
    </footer>
</body>
</html>
