<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | JobConnect</title>
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
                        <li><a href="../MAIN/main_joblisting.php">Job Listings</a></li>
                        <li><a href="../MAIN/main_aboutus.php">About Us</a></li>
                        <li class="current"></li><a href="../MAIN/main_contact.php">Contact</a></li>
                        <li><a href="../LOGIN/login.php">Login</a></li>
                        <li><a href="../SIGNUP/signup.php">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="contact-container">
            <h2>Contact Us</h2>
            <form action="contact.php" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 JobConnect. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>