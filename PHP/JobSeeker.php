<?php

class JobSeeker {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new job-seeker profile
    public function register($user_id, $resume, $skills, $experience, $location, $phone_number, $profile_picture_url) {
        try {
            $sql = "INSERT INTO job_seekers (user_id, resume, skills, experience, location, phone_number, profile_picture_url)
                    VALUES (:user_id, :resume, :skills, :experience, :location, :phone_number, :profile_picture_url)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':resume', $resume);
            $stmt->bindParam(':skills', $skills);
            $stmt->bindParam(':experience', $experience);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':profile_picture_url', $profile_picture_url);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    // Retrieve job-seeker profile by user ID
    public function getProfile($user_id) {
        try {
            $sql = "SELECT * FROM job_seekers WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    // Update job-seeker profile
    public function updateProfile($user_id, $resume, $skills, $experience, $location, $phone_number, $profile_picture_url) {
        try {
            $sql = "UPDATE job_seekers 
                    SET resume = :resume, skills = :skills, experience = :experience, location = :location, phone_number = :phone_number, profile_picture_url = :profile_picture_url 
                    WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':resume', $resume);
            $stmt->bindParam(':skills', $skills);
            $stmt->bindParam(':experience', $experience);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':profile_picture_url', $profile_picture_url);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
