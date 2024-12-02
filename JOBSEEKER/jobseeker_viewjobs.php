

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobConnect - Your Gateway to Career Opportunities</title>

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

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


     <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
        });

        $(document).ready(function() {
            $('#userTable2').DataTable();
        });
    </script>



    <link rel="stylesheet" href="main.css">

</head>
<body>
<header>
        <h1>List of Job</h1>
        <nav>
            <ul>
                <li><a href="../JOBSEEKER/jobseeker_profile.php">Profile</a></li>
                <li><a href="../JOBSEEKER/jobseeker_viewjobs.php">View Jobs</a></li>
                <li><a href="../LOGIN/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main  style="display:block;">
      

        <div class="content_1">
        <div class="tables m-5 p-3">
        <table id="userTable" class="display">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Requirements</th>
                    <th>Location</th>
                    <th>Salary Monthly</th>
                    <th>Status</th>
                

                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../DATABASE/dbConnection.php';
                require_once '../EMPLOYER/jobCrud.php';

                $database = new Database();
                $db = $database->getConnect();

                $jobposting = new JobPosting($db);
                $stmt = $jobposting->read();
                $num = $stmt->rowCount();

                if($num > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr>";
                      
                        echo "<td>" .(isset($row['job_title']) ? htmlspecialchars($row['job_title']) : ''). "</td>";
                        echo "<td>" .(isset($row['description']) ? htmlspecialchars($row['description']) : ''). "</td>";
                        echo "<td>" .(isset($row['requirements']) ? htmlspecialchars($row['requirements']) : ''). "</td>";
                        echo "<td>" .(isset($row['location']) ? htmlspecialchars($row['location']) : ''). "</td>";
                        echo "<td>" .(isset($row['salary']) ? htmlspecialchars($row['salary']) : ''). "</td>";
                        echo "<td>" .(isset($row['status']) ? htmlspecialchars($row['status']) : ''). "</td>";
                        echo "<td><a href=''>APPLY</a></td>";
                        echo "</tr>";
                    }
                }

                ?>
    
            </tbody>
        </table>
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


