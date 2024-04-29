document.addEventListener('DOMContentLoaded', function() {
    setupValidation('login-email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value)); // Set up validation for login email
    setupValidation('login-password', "Password cannot be empty.", value => value.length > 0); // Set up validation for login password
});

function setupValidation(fieldId, errorMessage, validationRule) {
    const input = document.getElementById(fieldId); // Get the input field
    const errorDiv = document.getElementById(`${fieldId}-error`); // Get the error div associated with the field

    input.addEventListener('input', function() {
        if (!validationRule(input.value)) { // Check if the input fails validation
            errorDiv.className = 'alert alert-danger'; // Display error alert
            errorDiv.textContent = errorMessage; // Set error message
        } else {
            errorDiv.className = ''; // Clear alert
            errorDiv.textContent = ''; // Clear message
        }
    });
}
