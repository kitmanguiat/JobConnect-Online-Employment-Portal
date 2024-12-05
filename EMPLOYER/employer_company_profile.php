<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/employerCrud.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit;
}

// Get the database connection
$database = new Database();
$db = $database->getConnect();

// Create Employer object
$employer = new Employer($db);

// Fetch the employer data
$employer->user_id = $_SESSION['user_id']; // Set user_id from session

// Assuming `read()` method is available to fetch employer info
$stmt = $employer->read();
$employer_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employer_data) {
    echo "No employer data found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
</head>
<body>
    <header>
        <nav class="section-navbar">
            <ul>
                <li><a href="../EMPLOYER/employer_dashboard.php">Dashboard</a></li>
                <li><a href="../EMPLOYER/employer_post_job.php">Post/Manage Job</a></li>
                <li><a href="../EMPLOYER/employer_view_applicants.php">View Applicants</a></li>
                <li><a href="../EMPLOYER/employer_company_profile.php">Company Profile</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <h1>Update Company Profile</h1>
    <form action="company_profile.php" method="POST" enctype="multipart/form-data">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($employer_data['company_name']); ?>" required><br>

        <label for="industry">Industry:</label>
        <input type="text" name="industry" value="<?php echo htmlspecialchars($employer_data['industry']); ?>" required><br>

        <label for="company_description">Description:</label>
        <textarea name="company_description" required><?php echo htmlspecialchars($employer_data['company_description']); ?></textarea><br>

        <label for="company_size">Company Size:</label>
        <input type="text" name="company_size" value="<?php echo htmlspecialchars($employer_data['company_size']); ?>" required><br>

        <label for="location">Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($employer_data['location']); ?>" required><br>

        <label for="founded_year">Founded Year:</label>
        <input type="number" name="founded_year" value="<?php echo htmlspecialchars($employer_data['founded_year']); ?>" required><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" value="<?php echo htmlspecialchars($employer_data['contact_number']); ?>" required><br>

        <label for="logo">Logo:</label>
        <input type="file" name="logo"><br>

        <?php if ($employer_data['logo']) : ?>
            <img src="<?php echo htmlspecialchars($employer_data['logo']); ?>" alt="Current Logo" style="max-width: 150px;"><br>
        <?php endif; ?>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>