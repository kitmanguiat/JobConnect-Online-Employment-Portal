<?php

class User {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password, $role) {
        try {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // SQL query to insert user
            $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);

            // Execute the query
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

    public function isEmailTaken($email) {
        try {
            $sql = "SELECT user_id FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return true;
        }
    }
}

class Login {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Existing methods (e.g., register)

    public function login($email, $password) {
        try {
            // Query to find the user by email
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Check if the user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Start the session and set session variables
                    session_start();

                    // Store the user information in session variables
                    $_SESSION['user_id'] = $user['id'];  // Set the user ID in session
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['created'] = $user['created_at'];  // Store the account creation time

                    // Debugging: Check session data
                    var_dump($_SESSION);  // Inspect session variables (make sure 'user_id' is set)

                    return $user; // Return user data on successful login
                } else {
                    // Password mismatch
                    return false;
                }
            } else {
                // No user found with the given email
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}


?>