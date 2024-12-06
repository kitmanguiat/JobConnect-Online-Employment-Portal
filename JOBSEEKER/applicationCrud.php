<?php

class JobApplication {
    private $conn;
    private $table = 'applications';

    public $application_id;
    public $job_posting_id;
    public $job_seeker_id;
    public $application_date;
    public $status;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to update the status of an application
    public function updateApplicationStatus() {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE application_id = :application_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':application_id', $this->application_id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        // Log the error if execution fails
        error_log("Error: " . implode(", ", $stmt->errorInfo()));
        return false;
    }
    // Method to create a new job application
    public function create() {
        $query = "INSERT INTO " . $this->table . " (job_posting_id, job_seeker_id, application_date, status)
                  VALUES (:job_posting_id, :job_seeker_id, :application_date, :status)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':job_posting_id', $this->job_posting_id);
        $stmt->bindParam(':job_seeker_id', $this->job_seeker_id);
        $stmt->bindParam(':application_date', $this->application_date);
        $stmt->bindParam(':status', $this->status);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        // Log the error if the execution fails
        error_log("Error: " . implode(", ", $stmt->errorInfo()));
        return false;
    }

    // Method to fetch applications by job seeker ID
    public function fetchByJobSeeker() {
        $query = "SELECT jp.job_title, jp.description, jp.requirements, jp.location, jp.salary, a.status 
                  FROM " . $this->table . " a
                  JOIN job_postings jp ON a.job_posting_id = jp.job_posting_id
                  WHERE a.job_seeker_id = :job_seeker_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':job_seeker_id', $this->job_seeker_id);

        $stmt->execute();

        return $stmt;
    }

    // Method to update the status of a job application
    public function updateStatus() {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE job_posting_id = :job_posting_id AND job_seeker_id = :job_seeker_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':job_posting_id', $this->job_posting_id);
        $stmt->bindParam(':job_seeker_id', $this->job_seeker_id);
        $stmt->bindParam(':status', $this->status);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        // Log the error if the execution fails
        error_log("Error: " . implode(", ", $stmt->errorInfo()));
        return false;
    }
}

class ApplicationManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnect();
    }

    public function getApplicationsByJobSeeker($userId) {
        $query = "
            SELECT 
                jp.job_title,
                jp.description,
                jp.location,
                jp.salary,
                a.application_date,
                a.status
            FROM applications a
            JOIN job_postings jp ON a.job_posting_id = jp.job_posting_id
            JOIN job_seekers js ON a.job_seeker_id = js.job_seeker_id
            WHERE js.user_id = :user_id
            ORDER BY a.application_date DESC
        ";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching applications: " . $e->getMessage());
        }
    }

    // Method to get applicants by job posting ID
    public function getApplicantsByJobPosting($jobPostingId) {
        $query = "
            SELECT 
                a.application_id,
                jp.job_title AS job_title,
                js.full_name AS job_seeker_name,
                js.availability,
                js.location,
                js.resume,
                js.phone_number,
                a.application_date,
                a.status
            FROM applications a
            JOIN job_seekers js ON a.job_seeker_id = js.job_seeker_id
            JOIN job_postings jp ON a.job_posting_id = jp.job_posting_id
            WHERE a.job_posting_id = :job_posting_id
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':job_posting_id', $jobPostingId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
}


?>