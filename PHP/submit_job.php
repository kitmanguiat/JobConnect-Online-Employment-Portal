<?php
require_once "../PHP/main_DB.php";

class JobPosting {
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

    // Method to post a new job
    public function postJob($jobTitle, $jobLocation, $jobDescription, $jobType, $salary) {
        if (!empty($jobTitle) && !empty($jobLocation) && !empty($jobDescription) && !empty($jobType) && $salary > 0) {
            $sql = "INSERT INTO jobs (job_title, job_location, job_description, job_type, salary, posted_date) 
                    VALUES (:job_title, :job_location, :job_description, :job_type, :salary, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':job_title', $jobTitle);
            $stmt->bindParam(':job_location', $jobLocation);
            $stmt->bindParam(':job_description', $jobDescription);
            $stmt->bindParam(':job_type', $jobType);
            $stmt->bindParam(':salary', $salary);

            try {
                $stmt->execute();
                header("Location: ../HTML/employer_dashboard.html");
                exit();
            } catch (PDOException $e) {
                error_log("Error posting job: " . $e->getMessage());
                echo "An error occurred while posting the job. Please try again later. Error: " . $e->getMessage();
            }
            
            
        } else {
            echo "All fields are required, and the salary must be greater than zero!";
        }
    }
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobTitle = htmlspecialchars($_POST['job_title']);
    $jobLocation = htmlspecialchars($_POST['job_location']);
    $jobDescription = htmlspecialchars($_POST['job_description']);
    $jobType = htmlspecialchars($_POST['job_type']);
    $salary = (float) $_POST['salary'];

    // Create an instance of the JobPosting class
    $jobPosting = new JobPosting($servername, $dbname, $username, $password);

    // Call the postJob method to handle the form submission
    $jobPosting->postJob($jobTitle, $jobLocation, $jobDescription, $jobType, $salary);
}
?>
