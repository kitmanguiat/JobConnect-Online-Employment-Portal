<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobConnect - Your Gateway to Career Opportunities</title>
    <link rel="stylesheet" href="../CSS/signup.css">
    <script src="../JS/signup.js"></script>
 
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><span>Job</span>Connect</div>
                <nav>
                    <ul>
                        <li><a href="../MAIN/index.php">Home</a></li>
                        <li><a href="../MAIN/main_joblisting.php">Job Listings</a></li>
                        <li><a href="../MAIN/main_aboutus.php">About Us</a></li>
                        <li><a href="../LOGIN/login.php">Login</a></li>
                        <li class="current"><a href="../SIGNUP/signup.php">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="signup-container">
            <h2>Create an Account</h2>
            <form action="../SIGNUP/signup_process.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="role">I am a:</label>
                <select id="role" name="role" required>
                    <option value="job-seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
        
                <button type="submit" name="signup">Signup</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 JobConnect. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>