<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Fetch applicants for a specific job posting
            const jobPostingId = 1; // Replace with dynamic ID
            fetchApplicants(jobPostingId);

            // Initialize DataTable
            $('#applicantsTable').DataTable();

            function fetchApplicants(jobPostingId) {
                $.ajax({
                    url: 'viewApplicants.php',
                    method: 'GET',
                    data: { job_posting_id: jobPostingId },
                    success: function(response) {
                        const data = JSON.parse(response);
                        const table = $('#applicantsTable').DataTable();
                        table.clear();
                        data.forEach(applicant => {
                            table.row.add([
                                applicant.application_id,
                                applicant.job_seeker_name,
                                applicant.application_date,
                                applicant.status,
                                `<button class="accept" data-id="${applicant.application_id}">Accept</button>
                                 <button class="reject" data-id="${applicant.application_id}">Reject</button>`
                            ]).draw();
                        });

                        // Handle Accept/Reject actions
                        $('.accept').on('click', function() {
                            updateStatus($(this).data('id'), 'accepted');
                        });

                        $('.reject').on('click', function() {
                            updateStatus($(this).data('id'), 'rejected');
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch applicants.', 'error');
                    }
                });
            }

            function updateStatus(applicationId, status) {
                $.ajax({
                    url: 'updateApplicationStatus.php',
                    method: 'POST',
                    data: { application_id: applicationId, status: status },
                    success: function(response) {
                        Swal.fire('Success', 'Application status updated.', 'success');
                        fetchApplicants(jobPostingId); // Refresh table
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update status.', 'error');
                    }
                });
            }
        });
    </script>
</head>
<body>
    <h2>Applicants List</h2>
    <table id="applicantsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Applicant Name</th>
                <th>Application Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</body>
</html>
