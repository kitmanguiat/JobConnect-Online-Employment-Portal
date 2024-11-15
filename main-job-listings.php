<?php
header('Content-Type: application/json');

// Database connection settings
$host = 'localhost';
$db = 'jobconnect'; // Replace with your database name
$user = 'root'; // Replace with your database user
$password = ''; // Replace with your database password

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get search parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$location = isset($_GET['location']) ? $conn->real_escape_string($_GET['location']) : '';

// Build SQL query with filters
$sql = "SELECT * FROM job_listings WHERE 1";
if ($search !== '') {
    $sql .= " AND (title LIKE '%$search%' OR company LIKE '%$search%')";
}
if ($location !== '') {
    $sql .= " AND location = '$location'";
}

$result = $conn->query($sql);

$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

echo json_encode($jobs);

$conn->close();
?>
