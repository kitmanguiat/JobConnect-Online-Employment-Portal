<?php
require_once "../PHP/main_DB.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the input data
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare the SQL query to fetch user data based on email
    $sql = "SELECT id, name, password, role FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Store session data
                $_SESSION['user_id'] = $id;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role == "job_seeker") {
                    header("Location: jobseeker_registration.html");
                } else if ($role == "employer") {
                    header("Location: employer_registration.html");
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='main_login.html';</script>";
            }
        } else {
            echo "<script>alert('No account found with that email.'); window.location.href='main_login.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement.'); window.location.href='main_login.html';</script>";
    }

    // Close the connection
    $conn->close();
}
?>
