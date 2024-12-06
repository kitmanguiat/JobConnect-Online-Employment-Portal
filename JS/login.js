// Wait for the DOM to be fully loaded before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form'); // The login form

    // Event listener for form submission
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        const email = document.getElementById('email').value; // Get email value
        const password = document.getElementById('password').value; // Get password value

        // Validate email and password fields
        if (!email || !password) {
            alert('Please fill in both email and password fields.');
            return;
        }

        // Check if email format is correct using a regular expression
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // If validation is successful, submit the form
        loginForm.submit();
    });
});
