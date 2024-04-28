document.addEventListener('DOMContentLoaded', function() {
    setupValidation('name', "Name must be at least 3 characters long.", value => value.length >= 3);
    setupValidation('email', "Enter a valid email address.", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,4}$/.test(value));
    setupValidation('password', "Password must be at least 6 characters long.", value => value.length >= 6);
    setupValidation('phone-number', "Phone number cannot be empty and must be between 7 and 15 digits long.", value => /^[0-9]{7,15}$/.test(value));

    // Additional function call to ensure the button's initial state is correct without disabling it if there are pre-existing errors.
    document.querySelectorAll('form').forEach(form => {
        checkFormValidity(form, form.querySelector('button[type="submit"]'));
    });
});

function setupValidation(fieldId, errorMessage, validationRule) {
    const input = document.getElementById(fieldId);
    const errorDiv = document.getElementById(`${fieldId}-error`);
    const form = input.closest('form');
    const submitButton = form.querySelector('button[type="submit"]');

    input.addEventListener('input', function() {
        if (!validationRule(input.value)) {
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = errorMessage;
            submitButton.disabled = true;
        } else {
            errorDiv.className = '';
            errorDiv.textContent = '';
            checkFormValidity(form, submitButton);
        }
    });
}

function checkFormValidity(form, submitButton) {
    const errors = form.querySelectorAll('.alert-danger');
    submitButton.disabled = errors.length > 0;
}
