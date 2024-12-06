document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const usernameInput = document.getElementById("username");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const roleSelect = document.getElementById("role");
    const signupContainer = document.querySelector(".signup-container");

    // Dynamically add extra fields based on the selected role
    roleSelect.addEventListener("change", (e) => {
        const existingField = document.getElementById("extra-field");
        if (existingField) existingField.remove();

        if (e.target.value === "employer") {
            const extraField = document.createElement("div");
            extraField.id = "extra-field";
            extraField.innerHTML = `
                <label for="company">Company Name:</label>
                <input type="text" id="company" name="company" required>
            `;
            signupContainer.appendChild(extraField);
        }
    });

    // Validate email format
    emailInput.addEventListener("input", () => {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(emailInput.value)) {
            emailInput.setCustomValidity("Please enter a valid email address.");
        } else {
            emailInput.setCustomValidity("");
        }
    });

    // Password strength validation
    passwordInput.addEventListener("input", () => {
        const passwordStrength = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
        if (!passwordStrength.test(passwordInput.value)) {
            passwordInput.setCustomValidity(
                "Password must be at least 8 characters long and include a mix of uppercase, lowercase, numbers, and special characters."
            );
        } else {
            passwordInput.setCustomValidity("");
        }
    });

    // Form submission handler
    form.addEventListener("submit", (e) => {
        if (!form.checkValidity()) {
            e.preventDefault();
            alert("Please complete the form correctly before submitting.");
        }
    });
});