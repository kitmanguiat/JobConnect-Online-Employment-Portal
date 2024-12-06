<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | JobConnect</title>
    <link rel="stylesheet" href="../CSS/login.css">
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
                        <li class="current"></li><a href="../LOGIN/login.php">Login</a></li>
                        <li><a href="../SIGNUP/signup.php">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="login-container">
            <h2>Login</h2>
    <form action="../LOGIN/login_process.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
            <p>Don't have an account? <a href="../SIGNUP/signup.php">Sign Up</a></p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 JobConnect. All rights reserved.</p>
        </div>
    </footer>

    <script src="../JS/main_login.js"></script>
</body>
</html>