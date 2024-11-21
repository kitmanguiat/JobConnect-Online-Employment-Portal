<?php
require_once "../PHP/dbjobconnect.php";
require_once "../PHP/employer_manage_job.php";

$jobManager = new JobManager('localhost', 'dbjobconnect', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['job_id'])) {
    $jobId = (int)$_GET['job_id'];
    $job = $jobManager->getJobById($jobId); // Create this method in JobManager

    if (!$job) {
        die("Job not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = (int)$_POST['job_id'];
    $jobTitle = htmlspecialchars($_POST['job_title']);
    $jobLocation = htmlspecialchars($_POST['job_location']);
    $jobDescription = htmlspecialchars($_POST['job_description']);
    $jobType = htmlspecialchars($_POST['job_type']);
    $salary = (float)$_POST['salary'];

    if ($jobManager->editJob($jobId, $jobTitle, $jobLocation, $jobDescription, $jobType, $salary)) {
        header("Location: ../PHP/employer_manage_job.php?status=edit-success");
        exit();
    } else {
        echo "Error updating job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job | JobConnect</title>
</head>
<body>
    <h2>Edit Job</h2>
    <form method="POST" action="edit_job.php">
        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
        <label for="job_title">Job Title:</label>
        <input type="text" name="job_title" id="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required><br>

        <label for="job_location">Location:</label>
        <input type="text" name="job_location" id="job_location" value="<?php echo htmlspecialchars($job['job_location']); ?>" required><br>

        <label for="job_description">Job Description:</label>
        <textarea name="job_description" id="job_description" rows="5" required><?php echo htmlspecialchars($job['job_description']); ?></textarea><br>

        <label for="job_type">Job Type:</label>
        <select name="job_type" id="job_type" required>
            <option value="full_time" <?php if ($job['job_type'] === 'full_time') echo 'selected'; ?>>Full Time</option>
            <option value="part_time" <?php if ($job['job_type'] === 'part_time') echo 'selected'; ?>>Part Time</option>
            <option value="contract" <?php if ($job['job_type'] === 'contract') echo 'selected'; ?>>Contract</option>
            <option value="internship" <?php if ($job['job_type'] === 'internship') echo 'selected'; ?>>Internship</option>
        </select><br>

        <label for="salary">Salary:</label>
        <input type="number" name="salary" id="salary" value="<?php echo htmlspecialchars($job['salary']); ?>" required><br>

        <button type="submit">Update Job</button>
    </form>
</body>
</html>
