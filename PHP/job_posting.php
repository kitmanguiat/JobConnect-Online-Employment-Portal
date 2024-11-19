<?php
class JobPosting {
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Method to fetch job postings by employer ID
    public function getJobPostingsByEmployer($employerId) {
        $sql = "SELECT job_title, job_location, job_description, job_type, salary, posted_date 
                FROM job_postings WHERE employer_id = :employer_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':employer_id', $employerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
