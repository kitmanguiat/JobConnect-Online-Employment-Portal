<?php

class JobApplication {
    private $conn;
    private $table = 'applications';

    public $job_posting_id;
    public $job_seeker_id;
    public $application_date;
    public $status;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
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
?>