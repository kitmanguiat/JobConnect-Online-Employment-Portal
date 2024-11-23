// Employer Registration Form Validation
document.getElementById('employerForm').addEventListener('submit', function (e) {
    const companyName = document.getElementById('company_name').value.trim();
    const industry = document.getElementById('industry').value.trim();
    const companyDescription = document.getElementById('company_description').value.trim();
    const companySize = document.getElementById('company_size').value;
    const location = document.getElementById('location').value.trim();
    const foundedYear = document.getElementById('founded_year').value;
    const contactNumber = document.getElementById('contact_number').value.trim();

    let errors = [];

    // Company Name Validation
    if (companyName === "") {
        errors.push("Company Name is required.");
    }

    // Industry Validation
    if (industry === "") {
        errors.push("Industry is required.");
    }

    // Company Description Validation
    if (companyDescription.length < 10) {
        errors.push("Company Description must be at least 10 characters.");
    }

    // Company Size Validation
    if (companySize === "") {
        errors.push("Please select a Company Size.");
    }

    // Location Validation
    if (location === "") {
        errors.push("Location is required.");
    }

    // Founded Year Validation
    const currentYear = new Date().getFullYear();
    if (foundedYear === "" || foundedYear < 1900 || foundedYear > currentYear) {
        errors.push("Founded Year must be between 1900 and " + currentYear + ".");
    }

    // Contact Number Validation
    const phoneRegex = /^[+]*[0-9]{1,4}[0-9]{7,12}$/;
    if (!phoneRegex.test(contactNumber)) {
        errors.push("Contact Number is invalid. Use a valid phone format.");
    }

    // Show Errors
    if (errors.length > 0) {
        e.preventDefault(); // Prevent form submission
        alert("Please fix the following errors:\n\n" + errors.join("\n"));
    }
});
