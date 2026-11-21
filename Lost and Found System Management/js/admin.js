
// Admin Login Form Validation

function validateAdminLoginForm() {

    let username = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;

    // Check empty username
    if (username === "") {
        alert("Please enter your admin username.");
        return false;
    }

    // Check empty password
    if (password.trim() === "") {
        alert("Please enter your password.");
        return false;
    }

    return true;
}
