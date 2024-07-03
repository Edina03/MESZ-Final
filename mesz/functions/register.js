function validateForm() {
    var emailInput = document.getElementById("email");
    var emailHelpBlock = document.getElementById("emailHelpBlock");
    var passwordInput = document.getElementById("password");
    var passwordConfirmInput = document.getElementById("passwordConfirm");
    var passwordHelpBlock = document.getElementById("passwordHelpBlock");
    var passwordConfirmHelpBlock = document.getElementById("passwordConfirmHelpBlock");
    var registerBtn = document.getElementById("registerBtn");

    var email = emailInput.value;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var password = passwordInput.value;
    var numberRegex = /\d/;
    var uppercaseRegex = /[A-Z]/;
    var specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    var passwordConfirm = passwordConfirmInput.value;

    var validEmail = emailRegex.test(email);
    var validPassword = numberRegex.test(password) && uppercaseRegex.test(password) /*&& specialCharRegex.test(password)*/;
    var passwordMatch = password === passwordConfirm;

    // Show or hide email validation message
    if (emailInput.value.trim() !== "") {
        emailHelpBlock.style.display = validEmail ? "none" : "block";
    }

    if (passwordInput.value.trim() !== "") {
        passwordHelpBlock.style.display = validPassword ? "none" : "block";
    }

    if (passwordConfirmInput.value.trim() !== "") {
        passwordConfirmHelpBlock.style.display = passwordMatch ? "none" : "block";
    }

    registerBtn.disabled = !(validEmail && validPassword && passwordMatch);

    return validEmail && validPassword && passwordMatch;
}

window.addEventListener("load", function () {
    var emailInput = document.getElementById("email");
    var passwordInput = document.getElementById("password");
    var passwordConfirmInput = document.getElementById("passwordConfirm");

    emailInput.addEventListener("input", validateForm);
    passwordInput.addEventListener("input", validateForm);
    passwordConfirmInput.addEventListener("input", validateForm);

    validateForm();
});

