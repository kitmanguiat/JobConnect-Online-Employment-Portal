<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link rel="stylesheet" href="../CSS/employer_post_job.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        #editJobModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }
        #editJobModal form {
            display: flex;
            flex-direction: column;
        }
        #editJobModal label, #editJobModal input, #editJobModal select, #editJobModal textarea {
            margin-bottom: 10px;
        }
        .close-modal {
            background: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>JobConnect</h1>
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
    <div class="form-container">
        <form id="jobForm">
            <h2>Post a Job</h2>
            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" id="job_title" required> <br><br>
            <label for="description">Job Description:</label>
            <textarea name="description" id="description" required></textarea> <br><br>
            <label for="requirements">Job Requirements:</label>
            <textarea name="requirements" id="requirements" required></textarea> <br><br>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required> <br><br>
            <label for="salary">Salary:</label>
            <input type="number" name="salary" id="salary" required> <br><br>
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select><br><br>
            <button type="submit">Post Job</button><br><br>
        </form>
    </div>

    <table id="jobTable">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Description</th>
                <th>Requirements</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Edit Job Modal -->
    <div id="editJobModal">
        <form id="editJobForm">
            <h2>Edit Job</h2>
            <input type="hidden" name="job_posting_id" id="edit_job_posting_id">
            <label for="edit_job_title">Job Title:</label>
            <input type="text" name="job_title" id="edit_job_title" required>
            <label for="edit_job_description">Job Description:</label>
            <textarea name="description" id="edit_job_description" required></textarea>
            <label for="edit_job_requirement">Job Requirements:</label>
            <textarea name="requirements" id="edit_job_requirement" required></textarea>
            <label for="edit_location">Location:</label>
            <input type="text" name="location" id="edit_location" required>
            <label for="edit_salary">Salary:</label>
            <input type="number" name="salary" id="edit_salary" required>
            <label for="edit_status">Status:</label>
            <select name="status" id="edit_status" required>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select>
            <button type="submit">Save Changes</button>
            <button type="button" class="close-modal">Cancel</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            const jobTable = $('#jobTable').DataTable({
                columns: [
                    { data: 'job_title' },
                    { data: 'description' },
                    { data: 'requirements' },
                    { data: 'location' },
                    { data: 'salary' },
                    { data: 'status' },
                    { data: 'actions', orderable: false, searchable: false }
                ]
            });

            const fetchJobs = () => {
                $.ajax({
                    url: '../EMPLOYER/fetch_job.php',
                    method: 'GET',
                    success: (data) => {
                        jobTable.clear().rows.add(JSON.parse(data)).draw();
                    },
                    error: (xhr) => Swal.fire('Error', `Failed to fetch jobs: ${xhr.responseText}`, 'error')
                });
            };

            fetchJobs();

            $('#jobForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '../EMPLOYER/create_job.php',
                    method: 'POST',
                    data: formData,
                    success: (response) => {
                        Swal.fire('Success', response.message, 'success');
                        fetchJobs();
                        $('#jobForm')[0].reset();
                    },
                    error: (xhr) => Swal.fire('Error', `Failed to post job: ${xhr.responseText}`, 'error')
                });
            });

            $('#jobTable').on('click', '.delete-btn', function () {
                const jobId = $(this).data('id');
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete this job?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../EMPLOYER/delete_job.php',
                            method: 'POST',
                            data: { job_posting_id: jobId },
                            success: (response) => {
                                Swal.fire('Deleted', response, 'success');
                                fetchJobs();
                            },
                            error: (xhr) => Swal.fire('Error', `Deletion failed: ${xhr.responseText}`, 'error')
                        });
                    }
                });
            });

            $('#jobTable').on('click', '.edit-btn', function () {
                const jobId = $(this).data('id');
                $.ajax({
                    url: '../EMPLOYER/get_job.php',
                    method: 'GET',
                    data: { job_posting_id: jobId },
                    success: (response) => {
                        const job = JSON.parse(response);
                        $('#edit_job_posting_id').val(job.job_posting_id);
                        $('#edit_job_title').val(job.job_title);
                        $('#edit_job_description').val(job.description);
                        $('#edit_job_requirement').val(job.requirements);
                        $('#edit_location').val(job.location);
                        $('#edit_salary').val(job.salary);
                        $('#edit_status').val(job.status);
                        $('#editJobModal').show();
                    },
                    error: (xhr) => Swal.fire('Error', `Unable to fetch job: ${xhr.responseText}`, 'error')
                });
            });

            $('#editJobForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '../EMPLOYER/edit_job.php',
                    method: 'POST',
                    data: formData,
                    success: (response) => {
                        Swal.fire('Updated', response, 'success');
                        fetchJobs();
                        $('#editJobModal').hide();
                    },
                    error: (xhr) => Swal.fire('Error', `Update failed: ${xhr.responseText}`, 'error')
                });
            });

            $('#editJobModal').on('click', '.close-modal', function () {
                $('#editJobModal').hide();
            });
        });
    </script>
</body>
</html>
