<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobConnect - Your Gateway to Career Opportunities</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
                
                <!-- Job Listings Table -->
                <table id="job-listings-table" class="job-listings-table display">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="job-listings"></tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        function fetchJobs() {
            document.getElementById("loading").style.display = "block"; // Show loading message

            const search = document.getElementById("search").value;
            const location = document.getElementById("location-filter").value;

            fetch(`../MAIN/FetchJobListings.php?search=${search}&location=${location}`)
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
                jobListings.innerHTML = "<tr><td colspan='4'>No job listings found.</td></tr>";
                return;
            }

            jobs.forEach(job => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${job.title}</td>
                    <td>${job.company}</td>
                    <td>${job.location}</td>
                    <td>${job.description}</td>
                `;

                jobListings.appendChild(row);
            });

            // Initialize DataTable after content is added
            $('#job-listings-table').DataTable();
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
