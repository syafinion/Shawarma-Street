document.addEventListener('DOMContentLoaded', function() {
    setupValidation('name', "Name must be at least 3 characters long.", value => value.length >= 3); // Validation for name field
    setupValidation('email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value)); // Validation for email field
    setupValidation('password', "Password must be at least 6 characters long.", value => value.length >= 6);  // Validation for password field
    setupValidation('phone-number', "Phone number cannot be empty and must be between 7 and 15 digits long.", value => /^[0-9]{7,15}$/.test(value)); // Validation for phone number

    // Additional function call to ensure the button's initial state is correct without disabling it if there are pre-existing errors.
    document.querySelectorAll('form').forEach(form => {
        checkFormValidity(form, form.querySelector('button[type="submit"]'));
    });
});

function setupValidation(fieldId, errorMessage, validationRule) {
    const input = document.getElementById(fieldId); // Get the input field
    const errorDiv = document.getElementById(`${fieldId}-error`); // Get the error div
    const form = input.closest('form'); // Get the parent form
    const submitButton = form.querySelector('button[type="submit"]'); // Get the submit button

    input.addEventListener('input', function() {
        if (!validationRule(input.value)) { // Check validation rule
            errorDiv.className = 'alert alert-danger'; // Display error alert
            errorDiv.textContent = errorMessage; // Set error message
            submitButton.disabled = true; // Disable submit button
        } else {
            errorDiv.className = ''; // Clear alert
            errorDiv.textContent = ''; // Clear message
            checkFormValidity(form, submitButton); // Check form validity
        }
    });
}

function checkFormValidity(form, submitButton) {
    const errors = form.querySelectorAll('.alert-danger'); // Check for error alerts
    submitButton.disabled = errors.length > 0; // Disable submit button if errors exist
}
