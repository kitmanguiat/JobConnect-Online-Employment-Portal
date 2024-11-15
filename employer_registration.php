<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "dbjobconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs and sanitize
    $tin = htmlspecialchars($_POST['tin']);
    $business_name = htmlspecialchars($_POST['business_name']);
    $trade_name = htmlspecialchars($_POST['trade_name']);
    $location_type = htmlspecialchars($_POST['location_type']);
    $employer_type = htmlspecialchars($_POST['employer_type']);
    $total_work_force = htmlspecialchars($_POST['total_work_force']);
    $line_of_business = htmlspecialchars($_POST['line_of_business']);
    $address = htmlspecialchars($_POST['address']);
    $barangay = htmlspecialchars($_POST['barangay']);
    $city = htmlspecialchars($_POST['city']);
    $province = htmlspecialchars($_POST['province']);
    $contact_person = htmlspecialchars($_POST['contact_person']);
    $position = htmlspecialchars($_POST['position']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $email = htmlspecialchars($_POST['email']);

    // Prepare SQL query to insert data
    $sql = "INSERT INTO employers (tin, business_name, trade_name, location_type, employer_type, total_work_force, line_of_business, address, barangay, city, province, contact_person, position, telephone, mobile, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssssssss", $tin, $business_name, $trade_name, $location_type, $employer_type, $total_work_force, $line_of_business, $address, $barangay, $city, $province, $contact_person, $position, $telephone, $mobile, $email);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Employer registration successful!'); window.location.href='employer_dashboard.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement.');</script>";
    }

    // Close connection
    $conn->close();
}
?>
