<?php
session_start();
require_once '../DATABASE/dbConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
    <link rel="stylesheet" href="../CSS/jobseeker_viewjob.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
    </header>

    <section>
        <h2>Available Job Postings</h2>
        <table id="jobTable">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Requirement</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    <script>
        $(document).ready(function() {
            // Fetch jobs asynchronously
            $.ajax({
                url: '../JOBSEEKER/get_jobs.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        // Append jobs to the table
                        response.forEach(function(job) {
                            var jobRow = `
                                <tr>
                                    <td>${job.job_title}</td>
                                    <td>${job.description}</td>
                                    <td>${job.requirements}</td>
                                    <td>${job.location}</td>
                                    <td>${job.salary}</td>
                                    <td>${job.status}</td>
                                    <td><button class="applyBtn" data-job-id="${job.job_posting_id}">Apply</button></td>
                                </tr>
                            `;
                            $('#jobTable tbody').append(jobRow);
                        });

                        // Initialize DataTable
                        $('#jobTable').DataTable();
                    } else {
                        $('#jobTable tbody').append('<tr><td colspan="7">No job postings available.</td></tr>');
                    }
                }
            });

            // Apply to job
            $(document).on('click', '.applyBtn', function() {
                var jobId = $(this).data('job-id');

                $.ajax({
                    url: '../JOBSEEKER/apply_job.php',
                    type: 'POST',
                    data: { job_posting_id: jobId, user_id: <?php echo $user_id; ?> },
                    success: function(response) {
                        // Use SweetAlert2 to display a message
                        if (response.indexOf("success") !== -1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Application Submitted',
                                text: 'You have successfully applied to the job.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Application Failed',
                                text: response,
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>