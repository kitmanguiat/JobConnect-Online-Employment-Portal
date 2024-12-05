<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobConnect - Your Gateway to Career Opportunities</title>
    <link rel="stylesheet" href="../CSS/main.css">

</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><span>Job</span>Connect</div>
                <nav>
                    <ul>
                        <li><a href="../MAIN/index.php">Home</a></li>
                        <li class="current"><a href="../MAIN/main_joblisting.php">Job Listings</a></li>
                        <li><a href="../MAIN/main_aboutus.php">About Us</a></li>
                        <li><a href="../MAIN/main_contact.php">Contact</a></li>
                        <li><a href="../LOGIN/login.php">Login</a></li>
                        <li><a href="../SIGNUP/signup.php">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Find Your Dream Job with JobConnect</h1>
                <p>JobConnect is an automated job and applicant matching system designed to streamline your job search and help employers find the right talent.</p>
            </div>
        </section>

        <div class="container">
            <div class="job-listings-container">
                <h2>Available Job Vacancies</h2>
                
                <!-- Loading Indicator -->
                <div id="loading">Loading jobs...</div>
                
                <!-- Search and Filter Section -->
                <div class="search-filter">
                    <input type="text" id="search" placeholder="Search jobs by title or company...">
                    <select id="location-filter">
                        <option value="">All Locations</option>
                        <option value="Makati">Makati</option>
                        <option value="Quezon City">Quezon City</option>
                        <option value="Taguig">Taguig</option>
                        <option value="Pasig">Pasig</option>
                        <option value="Mandaluyong">Mandaluyong</option>
                        <option value="Manila">Manila</option>
                    </select>
                    <button onclick="fetchJobs()">Search</button>
                </div>
                
                <!-- Job Listings -->
                <div id="job-listings"></div>
            </div>
        </div>
    </main>

    <script>
        function fetchJobs() {
            document.getElementById("loading").style.display = "block"; // Show loading message

            const search = document.getElementById("search").value;
            const location = document.getElementById("location-filter").value;

            fetch(`job-listings.php?search=${search}&location=${location}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("loading").style.display = "none"; // Hide loading message
                    displayJobs(data);
                });
        }

        function displayJobs(jobs) {
            const jobListings = document.getElementById("job-listings");
            jobListings.innerHTML = ""; // Clear previous results

            if (jobs.length === 0) {
                jobListings.innerHTML = "<p>No job listings found.</p>";
                return;
            }

            jobs.forEach(job => {
                const jobDiv = document.createElement("div");
                jobDiv.classList.add("job-listing");

                jobDiv.innerHTML = `
                    <h3>${job.title}</h3>
                    <p><strong>Company:</strong> ${job.company}</p>
                    <p><strong>Location:</strong> ${job.location}</p>
                    <p>${job.description}</p>
                `;

                jobListings.appendChild(jobDiv);
            });
        }

        // Fetch all jobs on page load
        document.addEventListener("DOMContentLoaded", fetchJobs);
    </script>

    <footer>
        <div class="container">
            <p>&copy; 2024 JobConnect. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
