document.getElementById('loginForm').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Basic email validation (client-side)
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
    }

    // Password validation (client-side)
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        event.preventDefault();
    }
});