// JavaScript for job seeker registration form validation
document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    // Fetch all forms with the "needs-validation" class
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over each form and apply custom validation
    Array.prototype.slice.call(forms).forEach((form) => {
        form.addEventListener(
            'submit',
            (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            },
            false
        );
    });

    // Additional file validation for the resume input
    const resumeInput = document.getElementById('resume');
    resumeInput.addEventListener('change', () => {
        const allowedExtensions = ['pdf', 'doc', 'docx'];
        const file = resumeInput.files[0];
        if (file) {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Invalid file format. Please upload a PDF, DOC, or DOCX file.');
                resumeInput.value = ''; // Clear the input
            }
        }
    });

    // Additional file validation for the profile picture input
    const profilePictureInput = document.getElementById('profile_picture');
    profilePictureInput.addEventListener('change', () => {
        const file = profilePictureInput.files[0];
        if (file) {
            const maxFileSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxFileSize) {
                alert('File size exceeds 2MB. Please upload a smaller file.');
                profilePictureInput.value = ''; // Clear the input
            }
        }
    });
});
