document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signupForm');
    const errorDiv = document.getElementById('error-messages');

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent form submission for validation

        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const role = document.getElementById('role').value;

        let errors = [];

        // Validate username
        if (username.length < 3) {
            errors.push("Username must be at least 3 characters long.");
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push("Please enter a valid email address.");
        }

        // Validate password
        if (password.length < 8) {
            errors.push("Password must be at least 6 characters long.");
        }

        // Validate role selection
        if (!role) {
            errors.push("Please select a role.");
        }

        // Display errors or submit form
        errorDiv.innerHTML = "";

        if (errors.length > 0) {
            errors.forEach(error => {
                const errorParagraph = document.createElement('p');
                errorParagraph.textContent = error;
                errorParagraph.style.color = 'red';
                errorDiv.appendChild(errorParagraph);
            });
        } else {
            form.submit(); // Submit form if no errors
        }
    });
});
