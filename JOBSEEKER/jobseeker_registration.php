<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Registration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Job Seeker Registration</h2>
        <form action="register_jobseeker.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
                <div class="invalid-feedback">Please enter your full name.</div>
            </div>

            <div class="mb-3">
                <label for="resume" class="form-label">Resume (PDF, DOC, DOCX):</label>
                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                <div class="invalid-feedback">Please upload your resume.</div>
            </div>

            <div class="mb-3">
                <label for="availability" class="form-label">Availability:</label>
                <select class="form-select" id="availability" name="availability" required>
                    <option value="">Choose...</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Freelance">Freelance</option>
                    <option value="Contract">Contract</option>
                    <option value="Internship">Internship</option>
                    <option value="Negotiable">Negotiable</option>
                </select>
                <div class="invalid-feedback">Please select your availability.</div>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Address:</label>
                <input type="text" class="form-control" id="location" name="location" required>
                <div class="invalid-feedback">Please enter your address.</div>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number:</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                <div class="invalid-feedback">Please enter your phone number.</div>
            </div>

            <div class="mb-3">
                <label for="profile_picture_url" class="form-label">Profile Picture:</label>
                <input type="file" class="form-control" id="profile_picture_url" name="profile_picture_url" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS (optional, but needed for form validation) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
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

