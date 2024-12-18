<?php
class Employer {
    private $conn;
    private const tbl_name = "employers";

    public $employer_id; 
    public $user_id;
    public $company_name;
    public $industry;
    public $company_description;
    public $company_size;
    public $location;
    public $founded_year;
    public $logo;
    public $contact_number;

    public function __construct($db) {
        $this->conn = $db;
    }

     // Get employer details by user ID
     public function getEmployerDetailsByUserId() {
        $query = "SELECT * FROM " . self::tbl_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch employer details
    }

    // Get employer information by user ID
    public function getEmployerByUserId() {
        $query = "SELECT * FROM " . self::tbl_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the employer info
    }

    // Fetch job postings for a specific employer
    public function getJobPostings() {
        $query = "SELECT * FROM job_postings WHERE employer_id = :employer_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employer_id', $this->employer_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all job postings
    }

    public function create() {
        $query = "INSERT INTO " . self::tbl_name . "
                  (user_id, company_name, industry, company_description, company_size, location, founded_year, logo, contact_number) 
                  VALUES (:user_id, :company_name, :industry, :company_description, :company_size, :location, :founded_year, :logo, :contact_number)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':industry', $this->industry);
        $stmt->bindParam(':company_description', $this->company_description);
        $stmt->bindParam(':company_size', $this->company_size);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':founded_year', $this->founded_year);
        $stmt->bindParam(':logo', $this->logo);
        $stmt->bindParam(':contact_number', $this->contact_number);

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . self::tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByUserId() {
        $query = "SELECT * FROM " . self::tbl_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . self::tbl_name . " 
                  SET company_name = :company_name, industry = :industry, 
                      company_description = :company_description, 
                      company_size = :company_size, location = :location, 
                      founded_year = :founded_year, contact_number = :contact_number";

        if (!empty($this->logo)) {
            $query .= ", logo = :logo";
        }

        $query .= " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':industry', $this->industry);
        $stmt->bindParam(':company_description', $this->company_description);
        $stmt->bindParam(':company_size', $this->company_size);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':founded_year', $this->founded_year);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':user_id', $this->user_id);

        if (!empty($this->logo)) {
            $stmt->bindParam(':logo', $this->logo);
        }

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete() {
        $query = "DELETE FROM " . self::tbl_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employer_id', $this->employer_id);

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>
