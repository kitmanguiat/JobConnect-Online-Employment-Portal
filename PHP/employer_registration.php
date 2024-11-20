<?php
require_once "../PHP/dbjobconnect.php";

class EmployerRegistration {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    // Sanitize input
    private function sanitizeInput($input) {
        return htmlspecialchars($this->conn->real_escape_string($input));
    }

    // Register employer
    public function registerEmployer($data) {
        // Sanitize inputs
        foreach ($data as $key => $value) {
            $data[$key] = $this->sanitizeInput($value);
        }

        // Prepare SQL query
        $sql = "INSERT INTO employers (tin, business_name, trade_name, location_type, employer_type, total_work_force, line_of_business, address, barangay, city, province, contact_person, position, telephone, mobile, email) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param(
                "ssssssssssssssss",
                $data['tin'],
                $data['business_name'],
                $data['trade_name'],
                $data['location_type'],
                $data['employer_type'],
                $data['total_work_force'],
                $data['line_of_business'],
                $data['address'],
                $data['barangay'],
                $data['city'],
                $data['province'],
                $data['contact_person'],
                $data['position'],
                $data['telephone'],
                $data['mobile'],
                $data['email']
            );

            // Execute the query
            if ($stmt->execute()) {
                $this->alertAndRedirect("Employer registration successful!", "../HTML/employer_dashboard.html");
            } else {
                $this->alertAndRedirect("Error: " . $stmt->error, "../HTML/employer_registration.html");
            }

            $stmt->close();
        } else {
            $this->alertAndRedirect("Failed to prepare the SQL statement.", "../HTML/employer_registration.html");
        }
    }

    // Helper function to show an alert and redirect
    private function alertAndRedirect($message, $redirect) {
        echo "<script>alert('$message'); window.location.href='$redirect';</script>";
        exit();
    }

    // Destructor to close the database connection
    public function __destruct() {
        $this->conn->close();
    }
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'tin' => $_POST['tin'],
        'business_name' => $_POST['business_name'],
        'trade_name' => $_POST['trade_name'],
        'location_type' => $_POST['location_type'],
        'employer_type' => $_POST['employer_type'],
        'total_work_force' => $_POST['total_work_force'],
        'line_of_business' => $_POST['line_of_business'],
        'address' => $_POST['address'],
        'barangay' => $_POST['barangay'],
        'city' => $_POST['city'],
        'province' => $_POST['province'],
        'contact_person' => $_POST['contact_person'],
        'position' => $_POST['position'],
        'telephone' => $_POST['telephone'],
        'mobile' => $_POST['mobile'],
        'email' => $_POST['email'],
    ];

    $employerRegistration = new EmployerRegistration('localhost', 'root', '', 'dbjobconnect');
    $employerRegistration->registerEmployer($data);
}
?>
