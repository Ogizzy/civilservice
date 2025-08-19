document.querySelector('.toggle-password').addEventListener('click', function() {
    const passwordField = document.querySelector('.password-field input');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        this.textContent = '👁';
    }
});
