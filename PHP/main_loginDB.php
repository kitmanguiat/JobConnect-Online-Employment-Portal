<?php
require_once "../PHP/dbjobconnect.php";

class LoginManager {
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

    // Authenticate user
    public function authenticate($email, $password) {
        // Sanitize inputs
        $email = $this->sanitizeInput($email);

        // Prepare SQL statement
        $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $name, $hashed_password, $role);
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $hashed_password)) {
                    $this->setSession($id, $name, $role);
                    $this->redirectByRole($role);
                } else {
                    $this->alertAndRedirect('Incorrect password.', 'main_login.html');
                }
            } else {
                $this->alertAndRedirect('No account found with that email.', 'main_login.html');
            }

            $stmt->close();
        } else {
            $this->alertAndRedirect('Failed to prepare the SQL statement.', 'main_login.html');
        }
    }

    // Set session data
    private function setSession($id, $name, $role) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;
    }

    // Redirect based on user role
    private function redirectByRole($role) {
        if ($role === "job_seeker") {
            header("Location: ../HTML/jobseeker_registration.html");
        } elseif ($role === "employer") {
            header("Location: ../HTML/employer_registration.html");
        }
        exit();
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
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginManager = new LoginManager('localhost', 'root', '', 'dbjobconnect');
    $loginManager->authenticate($email, $password);
}
?>
