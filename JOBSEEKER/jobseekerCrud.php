<?php
class JobSeeker {
    private $conn;
    private $table = 'job_seekers';

    public $user_id;
    public $full_name;
    public $availability;
    public $location;
    public $phone_number;
    public $resume;
    public $profile_picture;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to create a new job seeker
    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, full_name, availability, location, phone_number, resume, profile_picture)
                  VALUES (:user_id, :full_name, :availability, :location, :phone_number, :resume, :profile_picture)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':availability', $this->availability);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':resume', $this->resume);
        $stmt->bindParam(':profile_picture', $this->profile_picture);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Method to update job seeker information
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET full_name = :full_name, availability = :availability, location = :location, phone_number = :phone_number,
                      resume = :resume, profile_picture = :profile_picture
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':availability', $this->availability);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':resume', $this->resume);
        $stmt->bindParam(':profile_picture', $this->profile_picture);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Method to fetch job seeker data by user ID
    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
