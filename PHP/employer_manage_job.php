<?php
require_once "../PHP/main_DB.php";

class JobManager {
    private $pdo;

    // Constructor to initialize the database connection
    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Method to fetch all job listings
    public function getAllJobs() {
        try {
            $sql = "SELECT id, job_title FROM jobs";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching jobs: " . $e->getMessage());
            return [];
        }
    }

    // Method to delete a job by ID
    public function deleteJob($jobId) {
        try {
            $sql = "DELETE FROM jobs WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting job: " . $e->getMessage());
            return false;
        }
    }

    public function getJobById($jobId) {
        try {
            $sql = "SELECT * FROM jobs WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching job: " . $e->getMessage());
            return null;
        }
    }
    
    // Method to edit a job (stub for implementation)
    public function editJob($jobId, $jobTitle, $jobLocation, $jobDescription, $jobType, $salary) {
        try {
            $sql = "UPDATE jobs SET job_title = :job_title, job_location = :job_location, 
                    job_description = :job_description, job_type = :job_type, salary = :salary
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
            $stmt->bindParam(':job_title', $jobTitle);
            $stmt->bindParam(':job_location', $jobLocation);
            $stmt->bindParam(':job_description', $jobDescription);
            $stmt->bindParam(':job_type', $jobType);
            $stmt->bindParam(':salary', $salary);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error updating job: " . $e->getMessage());
            return false;
        }
    }
}

// Example usage
$jobManager = new JobManager('localhost', 'dbjobconnect', 'root', '');

// Fetch all job listings
$jobs = $jobManager->getAllJobs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/employer_manage_job.css">
    <title>Manage Job Listings | JobConnect</title>
</head>

<nav class="navbar">
        <div class="logo">JobConnect</div>
        <ul class="nav-links">
            <li><a href="../HTML/employer_interface.html">Dashboard</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="main-content">
            <header>
                <h1>Hello, <?php echo htmlspecialchars($business_name); ?></h1>
            </header>

            <nav class="section-navbar">
                <ul>
                    <li class="current"></li><a href="../HTML/employer_post_job.html">Post a Job</a></li>
                    <li><a href="../HTML/employer_manage_job.html">Manage Job Listings</a></li>
                    <li><a href="../HTML/employer_view_applicants.html">View Applicants</a></li>
                    <li><a href="../HTML/employer_company_profile.html">Company Profile</a></li>
                </ul>
            </nav>

<body>
    <h2>Manage Your Job Listings</h2>
    <ul class="job-list">
    <?php foreach ($jobs as $job): ?>
        <li class="job-item">
            <span><?php echo htmlspecialchars($job['job_title']); ?></span>
            <div class="job-actions">
                <form method="POST" action="../PHP/delete_job.php" style="display:inline;">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
                <form method="GET" action="../PHP/edit_job.php" style="display:inline;">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit">Edit</button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
