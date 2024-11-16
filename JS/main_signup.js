document.querySelector("form").addEventListener("submit", function(event) {
    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
    }
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        event.preventDefault();
    }
});