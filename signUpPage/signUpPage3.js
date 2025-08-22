window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");

    setTimeout(() => {
        loader.classList.add("loader-hidden");
    }, 2500);

    loader.addEventListener("transitionend", () => {
        if (loader && loader.parentNode) {
            loader.parentNode.removeChild(loader);
        }
    });
});


function togglePasswordVisibility() {
    var passwordField = document.getElementById('createAccountBoxPassword');
    var passwordToggleIcon = document.getElementById('createAccountBoxPasswordToggleIcon');

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