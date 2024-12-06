// Wait for the DOM to be fully loaded before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.querySelector('form'); // The signup form

    // Event listener for form submission
    signupForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        const username = document.getElementById('username').value; // Get username value
        const email = document.getElementById('email').value; // Get email value
        const password = document.getElementById('password').value; // Get password value
        const role = document.getElementById('role').value; // Get role value

        // Validate all fields are filled
        if (!username || !email || !password || !role) {
            alert('Please fill in all fields.');
            return;
        }

        // Validate email format
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // Check password strength (at least 6 characters)
        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            return;
        }

        // If all validations pass, submit the form
        signupForm.submit();
    });
});
