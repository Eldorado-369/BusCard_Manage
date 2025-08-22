window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");

    setTimeout(() => {
        loader.classList.add("loader-hidden");
    }, 2500);

    loader.addEventListener("transitionend", () => {
        document.body.removeChild("loader");
    })
})

function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var passwordToggleIcon = document.getElementById('passwordToggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggleIcon.classList.remove('fa-eye');
        passwordToggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordToggleIcon.classList.remove('fa-eye-slash');
        passwordToggleIcon.classList.add('fa-eye');
    }
}