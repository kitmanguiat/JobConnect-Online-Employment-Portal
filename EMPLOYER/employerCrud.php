<?php
class Employer {
    private $conn;
    private $tbl_name = "employers";

    public $id; 
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

    // Insert new employer
    public function create() {
        $query = "INSERT INTO " . $this->tbl_name . " 
                  (user_id, company_name, industry, company_description, company_size, location, founded_year, logo, contact_number) 
                  VALUES 
                  (:user_id, :company_name, :industry, :company_description, :company_size, :location, :founded_year, :logo, :contact_number)";
        
        $stmt = $this->conn->prepare($query);

        // Bind parameters
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
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Log or display error message
            return false;
        }
    }

    // Read all employers
    public function read() {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

// Update employer
public function update() {
    $query = "UPDATE " . $this->tbl_name . " 
              SET company_name = :company_name, 
                  industry = :industry, 
                  company_description = :company_description, 
                  company_size = :company_size, 
                  location = :location, 
                  founded_year = :founded_year, 
                  logo = :logo, 
                  contact_number = :contact_number
              WHERE user_id = :user_id";  // Make sure we're updating by user_id

    $stmt = $this->conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':company_name', $this->company_name);
    $stmt->bindParam(':industry', $this->industry);
    $stmt->bindParam(':company_description', $this->company_description);
    $stmt->bindParam(':company_size', $this->company_size);
    $stmt->bindParam(':location', $this->location);
    $stmt->bindParam(':founded_year', $this->founded_year);
    $stmt->bindParam(':logo', $this->logo);
    $stmt->bindParam(':contact_number', $this->contact_number);
    $stmt->bindParam(':user_id', $this->user_id); // Ensure we're updating the current user's profile

    return $stmt->execute();
}


    // Delete employer
    public function delete() {
        $query = "DELETE FROM " . $this->tbl_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Log or display error message
            return false;
        }
    }
}
?>