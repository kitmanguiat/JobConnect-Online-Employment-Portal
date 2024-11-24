<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job-Seeker Registration</title>
</head>
<body>
    <h1>Job-Seeker Registration</h1>
    <form action="../PHP/JobSeekerController.php" method="POST" enctype="multipart/form-data">
        <label for="resume">Resume (Text):</label><br>
        <textarea name="resume" rows="5" required></textarea><br>

        <label for="skills">Skills (comma-separated):</label><br>
        <input type="text" name="skills" required><br>

        <label for="experience">Experience:</label><br>
        <textarea name="experience" rows="5" required></textarea><br>

        <label for="location">Location:</label><br>
        <input type="text" name="location" required><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" name="phone_number" required><br>

        <label for="profile_picture">Profile Picture:</label><br>
        <input type="file" name="profile_picture" accept="image/*"><br><br>

        <button type="submit" name="register_jobseeker">Register</button>
    </form>
</body>
</html>
