<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Registration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="../JS/employer_registration.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Employer Registration</h2>
        <form action="../EMPLOYER/registration_proces.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="company_name" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                </div>
                <div class="col-md-6">
                    <label for="industry" class="form-label">Industry:</label>
                    <input type="text" class="form-control" id="industry" name="industry" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="company_description" class="form-label">Company Description:</label>
                <textarea class="form-control" id="company_description" name="company_description" rows="4"></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="company_size" class="form-label">Company Size:</label>
                    <select class="form-select" id="company_size" name="company_size" required>
                        <option value="1-10">1-10</option>
                        <option value="11-50">11-50</option>
                        <option value="51-200">51-200</option>
                        <option value="201-500">201-500</option>
                        <option value="500+">500+</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="location" class="form-label">Location:</label>
                    <input type="text" class="form-control" id="location" name="location">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="founded_year" class="form-label">Founded Year:</label>
                    <input type="number" class="form-control" id="founded_year" name="founded_year" min="1900" max="2024">
                </div>
                <div class="col-md-6">
                    <label for="contact_number" class="form-label">Contact Number:</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Company Logo:</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" name="register_employer">Register</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS (optional, but needed for certain Bootstrap features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>