<?php
session_start();
require_once '../DATABASE/dbConnection.php';
require_once '../JOBSEEKER/applicationCrud.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to view your applications.";
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $applicationManager = new ApplicationManager();
    $applications = $applicationManager->getApplicationsByJobSeeker($userId);
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Applications</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                responsive: true,
                paging: true,
                search: true,
                order: [[4, 'desc']], // Sort by application date by default
                language: {
                    emptyTable: "You haven't applied for any jobs yet.",
                }
            });
        });
    </script>
</head>
<body>
<header>
    <h1>Job Seeker Dashboard</h1>
        <nav>
            <ul>
                <li><a href="../JOBSEEKER/jobseeker_dashboard.php">Dashboard</a></li>
                <li><a href="../JOBSEEKER/jobseeker_profile.php">Profile</a></li>
                <li><a href="../JOBSEEKER/jobseeker_viewjob.php">View Jobs</a></li>
                <li><a href="../JOBSEEKER/jobseeker_my_applications.php">View Application</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    <main>
        <section>
            <h2>My Job Applications</h2>
            <table id="applicationsTable" class="display">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applications)) : ?>
                        <?php foreach ($applications as $application) : ?>
                            <tr>
                                <td><?= htmlspecialchars($application['job_title']) ?></td>
                                <td><?= htmlspecialchars(substr($application['description'], 0, 50)) ?>...</td>
                                <td><?= htmlspecialchars($application['location']) ?></td>
                                <td><?= htmlspecialchars(number_format($application['salary'], 2)) ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($application['application_date']))) ?></td>
                                <td>
                                    <?php if ($application['status'] === 'accepted') : ?>
                                        <span class="status accepted">✔ Accepted</span>
                                    <?php elseif ($application['status'] === 'rejected') : ?>
                                        <span class="status rejected">✘ Rejected</span>
                                    <?php else : ?>
                                        <span class="status pending">⏳ Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">You haven't applied for any jobs yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
