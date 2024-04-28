document.addEventListener('DOMContentLoaded', function() {
    setupValidation('name', "Name must be at least 3 characters long.", value => value.length >= 3);
    setupValidation('email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value));
    setupValidation('password', "Password must be at least 6 characters long.", value => value.length >= 6);
    setupValidation('phone-number', "Phone number must be between 7 and 15 digits long.", value => value.length >= 7 && value.length <= 15, true);

    setupValidation('login-email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value));
    setupValidation('login-password', "Password cannot be empty.", value => value.length > 0);
});

function setupValidation(fieldId, errorMessage, validationRule, numericOnly = false) {
    const input = document.getElementById(fieldId);
    const errorDiv = document.getElementById(`${fieldId}-error`);

    input.addEventListener('input', function() {
        let value = input.value;
        if (numericOnly) {
            value = value.replace(/[^0-9]/g, ''); // Clean non-numeric chars if needed
            input.value = value;
        }

        if (!validationRule(value)) {
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = errorMessage;
        } else {
            errorDiv.className = '';
            errorDiv.textContent = '';
        }
    });
}
