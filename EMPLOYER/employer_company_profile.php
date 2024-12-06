<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../EMPLOYER/employerCrud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnect();

$employer = new Employer($db);
$employer->user_id = $_SESSION['user_id'];

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
    <link rel="stylesheet" href="../CSS/employer_company_profile.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>JobConnect</h1>
            <nav>
                <ul>
                    <li><a href="../EMPLOYER/employer_dashboard.php">Dashboard</a></li>
                    <li><a href="../EMPLOYER/employer_post_job.php">Post/Manage Job</a></li>
                    <li><a href="../EMPLOYER/employer_view_applicants.php">View Applicants</a></li>
                    <li><a href="../EMPLOYER/employer_company_profile.php">Company Profile</a></li>
                    <li><a href="../LOGIN/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="profile">
                <h2>Update Company Profile</h2>
                <form action="company_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="company_name">Company Name:</label>
                        <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($employer_data['company_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="industry">Industry:</label>
                        <input type="text" id="industry" name="industry" value="<?php echo htmlspecialchars($employer_data['industry']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="company_description">Description:</label>
                        <textarea id="company_description" name="company_description" required><?php echo htmlspecialchars($employer_data['company_description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="company_size">Company Size:</label>
                            <select id="company_size" name="company_size" required>
                            <option value="1-10" <?php echo ($employer_data['company_size'] == '1-10') ? 'selected' : ''; ?>>1-10</option>
                            <option value="11-50" <?php echo ($employer_data['company_size'] == '11-50') ? 'selected' : ''; ?>>11-50</option>
                            <option value="51-200" <?php echo ($employer_data['company_size'] == '51-200') ? 'selected' : ''; ?>>51-200</option>
                            <option value="201-500" <?php echo ($employer_data['company_size'] == '201-500') ? 'selected' : ''; ?>>201-500</option>
                            <option value="500+" <?php echo ($employer_data['company_size'] == '500+') ? 'selected' : ''; ?>>500+</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($employer_data['location']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="founded_year">Founded Year:</label>
                        <input type="number" id="founded_year" name="founded_year" value="<?php echo htmlspecialchars($employer_data['founded_year']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($employer_data['contact_number']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" id="logo" name="logo">
                    </div>

                    <?php if ($employer_data['logo']) : ?>
                        <div class="form-group">
                            <img src="<?php echo htmlspecialchars($employer_data['logo']); ?>" alt="Current Logo" class="company-logo">
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 JobConnect. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>