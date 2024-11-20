<?php
require_once "../PHP/dbjobconnect.php";

class UserRegistration {
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
        return $this->conn->real_escape_string($input);
    }

    // Validate password length
    private function validatePassword($password) {
        if (strlen($password) < 8) {
            $this->alertAndRedirect("Password must be at least 8 characters long.", "../HTML/signup_form.html");
        }
    }

    // Register the user
    public function registerUser($name, $email, $password, $role) {
        // Sanitize inputs
        $name = $this->sanitizeInput($name);
        $email = $this->sanitizeInput($email);
        $role = $this->sanitizeInput($role);

        // Validate password
        $this->validatePassword($password);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            // Execute the query
            if ($stmt->execute()) {
                $this->alertAndRedirect("Sign up successful!", "../HTML/main_login.html");
            } else {
                $this->alertAndRedirect("Error: " . $stmt->error, "../HTML/signup_form.html");
            }

            $stmt->close();
        } else {
            $this->alertAndRedirect("Failed to prepare the SQL statement.", "../HTML/signup_form.html");
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $userRegistration = new UserRegistration('localhost', 'root', '', 'dbjobconnect');
    $userRegistration->registerUser($name, $email, $password, $role);
}
?>
