<?php

class JobPosting {
    private $conn;
    private $tbl_name = "job_postings";

    public $job_posting_id;
    public $employer_id;
    public $job_title;
    public $description;
    public $requirements;
    public $location;
    public $salary;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert new job posting
    public function create() {
        $query = "INSERT INTO " . $this->tbl_name . " (employer_id, job_title, description, requirements, location, salary, status) 
                  VALUES (:employer_id, :job_title, :description, :requirements, :location, :salary, :status)";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':employer_id', $this->employer_id);
        $stmt->bindParam(':job_title', $this->job_title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':requirements', $this->requirements);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':salary', $this->salary);
        $stmt->bindParam(':status', $this->status);
    
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            file_put_contents("error_log.txt", $e->getMessage(), FILE_APPEND); // Log the error
            return false;
        }
    }

    // Read all job postings
    public function read() {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Update job posting
    public function update() {
        $query = "UPDATE " . $this->tbl_name . " SET job_title = :job_title, description = :description, requirements = :requirements, 
                  location = :location, salary = :salary, status = :status WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':job_title', $this->job_title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':requirements', $this->requirements);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':salary', $this->salary);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':job_posting_id', $this->job_posting_id);

        return $stmt->execute();
    }

    // Delete job posting
    public function delete() {
        $query = "DELETE FROM " . $this->tbl_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':job_posting_id', $this->job_posting_id);

        return $stmt->execute();
    }
}
?>