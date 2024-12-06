document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    // Create a toggle icon for password visibility (eye icon)
    const togglePasswordIcon = document.createElement("i");
    togglePasswordIcon.classList.add("fas", "fa-eye");
    togglePasswordIcon.style.cursor = "pointer";
    togglePasswordIcon.style.marginLeft = "10px";
    passwordInput.parentNode.appendChild(togglePasswordIcon);

    togglePasswordIcon.addEventListener("click", () => {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePasswordIcon.classList.remove("fa-eye");
            togglePasswordIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            togglePasswordIcon.classList.remove("fa-eye-slash");
            togglePasswordIcon.classList.add("fa-eye");
        }
    });

    form.addEventListener("submit", (e) => {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email || !password) {
            e.preventDefault();
            alert("Both email and password are required.");
            return;
        }

        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert("Please enter a valid email address.");
            return;
        }
    });
});
