<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job-Seeker Dashboard | JobConnect</title>
    <link rel="stylesheet" href="../CSS/employer.css">
</head>
<body>
    <header>
        <h1>Welcome to JobConnect, [User’s Name]</h1>
    </header>
    
    <nav class="navbar">
        <ul>
            <li><a href="#recommended-jobs">Recommended Jobs</a></li>
            <li><a href="#saved-jobs">Saved Jobs</a></li>
            <li><a href="#applications">My Applications</a></li>
            <li><a href="#profile">Profile</a></li>
        </ul>
    </nav>
    
    <section id="recommended-jobs">
        <h2>Recommended Jobs</h2>
        <div class="job-listings">
            <!-- Recommended jobs will be dynamically loaded here -->
        </div>
    </section>

    <section id="saved-jobs">
        <h2>Saved Jobs</h2>
        <div class="job-listings">
            <!-- Saved jobs will be dynamically loaded here -->
        </div>
    </section>

    <section id="applications">
        <h2>My Applications</h2>
        <div class="application-status">
            <!-- Application status updates will be dynamically loaded here -->
        </div>
    </section>

    <section id="profile">
        <h2>My Profile</h2>
        <form action="update-profile.php" method="POST" enctype="multipart/form-data">
            <label for="resume">Upload/Update Resume:</label>
            <input type="file" name="resume" required>
            
            <label for="bio">Bio:</label>
            <textarea name="bio" rows="4"></textarea>

            <label for="skills">Skills:</label>
            <input type="text" name="skills" placeholder="e.g., JavaScript, PHP, Project Management">
            
            <button type="submit">Update Profile</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 JobConnect. All Rights Reserved.</p>
    </footer>
    
    <script src="job-seeker.js"></script> <!-- JavaScript for dynamic loading -->
</body>
</html>
