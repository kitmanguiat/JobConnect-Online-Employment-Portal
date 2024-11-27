<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Posting Management (Async)</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <nav class="section-navbar">
            <ul>
                <li><a href="../EMPLOYER/employer_post_job.php">Post/Manage Job</a></li>
                <li><a href="../EMPLOYER/employer_view_applicants.php">View Applicants</a></li>
                <li><a href="../EMPLOYER/employer_company_profile.php">Company Profile</a></li>
                <li><a href="../EMPLOYER/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <h2>Create Job Posting</h2>
    <form id="createJobForm">
        Job Title: <input type="text" name="job_title" required>
        <br><br>
        Description: <textarea name="description" required></textarea>
        <br><br>
        Requirements: <textarea name="requirements" required></textarea>
        <br><br>
        Location: <input type="text" name="location" required>
        <br><br>
        Salary: <input type="number" name="salary" required>
        <br><br>
        Status: 
        <select name="status" required>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
        </select>
        <br><br>
        <button type="submit">Create</button>
    </form>

    <h2>Job Postings List</h2>
    <table id="jobTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Job Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be dynamically populated here -->
        </tbody>
    </table>

    <!-- Edit Job Modal -->
    <div id="editJobModal" style="display: none;">
        <h2>Edit Job Posting</h2>
        <form id="editJobForm">
            <input type="hidden" name="id" id="editJobId">
            Job Title: <input type="text" name="job_title" id="editJobTitle" required>
            <br><br>
            Description: <textarea name="description" id="editDescription" required></textarea>
            <br><br>
            Requirements: <textarea name="requirements" id="editRequirements" required></textarea>
            <br><br>
            Location: <input type="text" name="location" id="editLocation" required>
            <br><br>
            Salary: <input type="number" name="salary" id="editSalary" required>
            <br><br>
            Status: 
            <select name="status" id="editStatus" required>
                <option value="active">Active</option>
                <option value="closed">Closed</option>
            </select>
            <br><br>
            <button type="submit">Save Changes</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            const jobTable = $('#jobTable').DataTable();

            // Fetch jobs on page load
            fetchJobs();

            // Handle form submission to create a job
            $('#createJobForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '../EMPLOYER/create_job.php',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire('Success', response, 'success');
                        fetchJobs();
                        $('#createJobForm')[0].reset();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to create job.', 'error');
                    }
                });
            });

            // Fetch and display jobs
            function fetchJobs() {
                $.ajax({
                    url: '../EMPLOYER/fetch_job.php',
                    method: 'GET',
                    success: function (data) {
                        jobTable.clear().rows.add(JSON.parse(data)).draw();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to fetch jobs.', 'error');
                    }
                });
            }

            // Handle edit action
            $('#jobTable').on('click', '.edit-btn', function () {
                const jobId = $(this).data('id');

                // Fetch job details
                $.ajax({
                    url: '../EMPLOYER/get_job.php',
                    method: 'GET',
                    data: { id: jobId },
                    success: function (data) {
                        const job = JSON.parse(data);
                        $('#editJobId').val(job.id);
                        $('#editJobTitle').val(job.job_title);
                        $('#editDescription').val(job.description);
                        $('#editRequirements').val(job.requirements);
                        $('#editLocation').val(job.location);
                        $('#editSalary').val(job.salary);
                        $('#editStatus').val(job.status);
                        $('#editJobModal').show();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to fetch job details.', 'error');
                    }
                });
            });

            // Handle form submission to edit a job
            $('#editJobForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '../EMPLOYER/edit_job.php',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire('Success', response, 'success');
                        fetchJobs();
                        $('#editJobModal').hide();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to edit job.', 'error');
                    }
                });
            });

            // Close the modal when clicking outside of it (optional enhancement)
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#editJobModal, .edit-btn').length) {
                    $('#editJobModal').hide();
                }
            });
        });
    </script>
</body>
</html>
