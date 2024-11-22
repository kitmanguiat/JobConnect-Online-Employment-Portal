<?php

class Employer {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registerEmployer($user_id, $company_name, $industry, $company_description, $company_size, $location, $founded_year, $logo, $contact_number) {
        try {
            // SQL query to insert employer details
            $sql = "INSERT INTO employers (user_id, company_name, industry, company_description, company_size, location, founded_year, logo, contact_number)
                    VALUES (:user_id, :company_name, :industry, :company_description, :company_size, :location, :founded_year, :logo, :contact_number)";
            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':industry', $industry);
            $stmt->bindParam(':company_description', $company_description);
            $stmt->bindParam(':company_size', $company_size);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':founded_year', $founded_year);
            $stmt->bindParam(':logo', $logo);
            $stmt->bindParam(':contact_number', $contact_number);

            // Execute query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
