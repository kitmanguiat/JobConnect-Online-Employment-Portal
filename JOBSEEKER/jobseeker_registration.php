<form action="register_jobseeker.php" method="POST" enctype="multipart/form-data">
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" required><br>

    <label for="resume">Resume (PDF, DOC, DOCX):</label>
    <input type="file" name="resume" accept=".pdf,.doc,.docx" required><br>

    <label for="availability">Availability:</label>
    <select name="availability" required>
        <option value="Full-time">Full-time</option>
        <option value="Part-time">Part-time</option>
        <option value="Freelance">Freelance</option>
        <option value="Contract">Contract</option>
        <option value="Internship">Internship</option>
        <option value="Negotiable">Negotiable</option>
    </select><br>

    <label for="location">Location:</label>
    <input type="text" name="location" required><br>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" required><br>

    <label for="profile_picture_url">Profile Picture:</label>
    <input type="file" name="profile_picture_url"><br>

    <button type="submit">Register</button>
</form>
