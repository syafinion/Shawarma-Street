document.addEventListener('DOMContentLoaded', function() {
    setupValidation('login-email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value));
    setupValidation('login-password', "Password cannot be empty.", value => value.length > 0);
});

function setupValidation(fieldId, errorMessage, validationRule) {
    const input = document.getElementById(fieldId);
    const errorDiv = document.getElementById(`${fieldId}-error`);

    input.addEventListener('input', function() {
        if (!validationRule(input.value)) {
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = errorMessage;
        } else {
            errorDiv.className = '';
            errorDiv.textContent = '';
        }
    });
}
