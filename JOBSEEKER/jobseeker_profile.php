<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/jobSeekerCrud.php';

// Get the database connection
$database = new Database();
$db = $database->getConnect();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Instantiate the JobSeeker class
$jobSeeker = new JobSeeker($db);

// Fetch job seeker details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM job_seekers WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$jobSeekerData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data is found
if (!$jobSeekerData) {
    echo "No job seeker data found.";
    exit;
}

// Handle profile update on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile data
    $jobSeeker->user_id = $user_id;
    $jobSeeker->full_name = htmlspecialchars(trim($_POST['full_name']));
    $jobSeeker->availability = htmlspecialchars(trim($_POST['availability']));
    $jobSeeker->location = htmlspecialchars(trim($_POST['location']));
    $jobSeeker->phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Handle resume upload
    $jobSeeker->resume = $jobSeekerData['resume']; // Default to current resume if not updated
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $resume_tmp = $_FILES['resume']['tmp_name'];
        $resume_name = $_FILES['resume']['name'];
        $resume_extension = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];

        if (in_array($resume_extension, $allowed_extensions)) {
            $resume_new_name = uniqid('', true) . '.' . $resume_extension;
            $resume_dir = '../uploads/resumes/';

            if (!file_exists($resume_dir)) {
                mkdir($resume_dir, 0777, true);
            }

            if (move_uploaded_file($resume_tmp, $resume_dir . $resume_new_name)) {
                $jobSeeker->resume = $resume_dir . $resume_new_name;
            } else {
                echo "Error uploading resume.";
                exit;
            }
        } else {
            echo "Invalid file type for resume.";
            exit;
        }
    }

    // Update the profile
    if ($jobSeeker->update()) {
        echo "Profile updated successfully!";
        header("Location: ../JOBSEEKER/jobseeker_dashboard.php"); // Redirect to dashboard after update
    } else {
        echo "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Seeker Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #35424a;
            --secondary-color: #2c3e50;
        }
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: var(--primary-color) !important;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(53, 66, 74, 0.25);
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Job Seeker Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="../JOBSEEKER/jobseeker_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link active" href="../JOBSEEKER/jobseeker_profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="../JOBSEEKER/jobseeker_viewjob.php">View Jobs</a></li>
                        <li class="nav-item"><a class="nav-link" href="../JOBSEEKER/jobseeker_my_applications.php">View Application</a></li>
                        <li class="nav-item"><a class="nav-link" href="../LOGIN/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <h2 class="mb-4 text-center" style="color: var(--primary-color);">Edit Your Profile</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="edit_profile.php" method="POST" enctype="multipart/form-data" class="needs-validation bg-white p-4 rounded shadow" novalidate>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($jobSeekerData['full_name']); ?>" required>
                        <div class="invalid-feedback">Please enter your full name.</div>
                    </div>

                    <div class="mb-3">
                        <label for="availability" class="form-label">Availability:</label>
                        <select class="form-select" id="availability" name="availability" required>
                            <option value="full-time" <?php echo ($jobSeekerData['availability'] == 'full-time') ? 'selected' : ''; ?>>Full-Time</option>
                            <option value="part-time" <?php echo ($jobSeekerData['availability'] == 'part-time') ? 'selected' : ''; ?>>Part-Time</option>
                            <option value="freelance" <?php echo ($jobSeekerData['availability'] == 'freelance') ? 'selected' : ''; ?>>Freelance</option>
                        </select>
                        <div class="invalid-feedback">Please select your availability.</div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Address:</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($jobSeekerData['location']); ?>" required>
                        <div class="invalid-feedback">Please enter your address.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number:</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($jobSeekerData['phone_number']); ?>" required>
                        <div class="invalid-feedback">Please enter your phone number.</div>
                    </div>

                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume (PDF, DOC, DOCX):</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx">
                        <div class="invalid-feedback">Please upload a valid resume file.</div>
                    </div>

                    <div class="mb-3">
                        <label for="profile_picture_url" class="form-label">Profile Picture:</label>
                        <input type="file" class="form-control" id="profile_picture_url" name="profile_picture_url" accept="image/*">
                        <div class="invalid-feedback">Please upload a valid image file.</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form validation script -->
    <script>
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>