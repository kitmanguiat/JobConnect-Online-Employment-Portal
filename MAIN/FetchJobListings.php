<?php
require_once '../DATABASE/dbConnection.php';

class JobListing {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchJobs($search = '', $location = '') {
        $query = "
            SELECT jp.job_title AS title, e.company_name AS company, jp.location, jp.description 
            FROM job_postings jp
            JOIN employers e ON jp.employer_id = e.employer_id
            WHERE jp.status = 'open'
        ";

        if (!empty($search)) {
            $query .= " AND (jp.job_title LIKE :search OR e.company_name LIKE :search)";
        }

        if (!empty($location)) {
            $query .= " AND jp.location = :location";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($location)) {
            $stmt->bindParam(':location', $location);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize database connection
try {
    $database = new Database();
    $db = $database->getConnect();

    $jobListing = new JobListing($db);

    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    $location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';

    $jobs = $jobListing->fetchJobs($search, $location);

    echo json_encode($jobs);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}