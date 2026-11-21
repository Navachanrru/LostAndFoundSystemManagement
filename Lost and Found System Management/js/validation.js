
// Registration Form Validation

document.addEventListener("DOMContentLoaded", function () {

    const registerForm = document.getElementById("registerForm");

    if (registerForm) {

        registerForm.addEventListener("submit", function (event) {

            const fullname = document.getElementById("fullname").value.trim();
            const email = document.getElementById("reg-email").value.trim();
            const userType = document.getElementById("user-type").value;
            const userID = document.getElementById("user-id").value.trim();
            const password = document.getElementById("reg-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;

            // Check full name
            if (fullname === "") {
                alert("Please enter your full name.");
                event.preventDefault();
                return;
            }

            // Check email
            if (email === "") {
                alert("Please enter your email address.");
                event.preventDefault();
                return;
            }

            // Check user type
            if (userType === "") {
                alert("Please select your user type.");
                event.preventDefault();
                return;
            }

            // Check student or staff ID
            if (userID === "") {
                alert("Please enter your Student / Staff ID.");
                event.preventDefault();
                return;
            }

            // Check password length
            if (password.length < 8) {
                alert("Password must contain at least 8 characters.");
                event.preventDefault();
                return;
            }

            // Check password confirmation
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                event.preventDefault();
                return;
            }

            alert("Registration form validation successful!");

        });

    }

});


// Admin Login Form Validation

document.addEventListener("DOMContentLoaded", function () {

    const adminForm = document.getElementById("adminLoginForm");

    if (adminForm) {

        adminForm.addEventListener("submit", function (event) {

            const username = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            if (username === "") {
                alert("Please enter your admin username.");
                event.preventDefault();
                return;
            }

            if (password.trim() === "") {
                alert("Please enter your password.");
                event.preventDefault();
                return;
            }

        });

    }

});

// Normal User Login Form Validation

document.addEventListener("DOMContentLoaded", function () {

    const loginForm = document.getElementById("loginForm");

    if (loginForm) {

        loginForm.addEventListener("submit", function (event) {

            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            if (email === "") {
                alert("Please enter your email address.");
                event.preventDefault();
                return;
            }

            if (password.trim() === "") {
                alert("Please enter your password.");
                event.preventDefault();
                return;
            }

            if (!email.includes("@") || !email.includes(".")) {
                alert("Please enter a valid email address.");
                event.preventDefault();
                return;
            }

        });

    }

});