
function validateRegisterForm() {

    // Get values from the registration form
    let fullname = document.getElementById("fullname").value.trim();
    let email = document.getElementById("reg-email").value.trim();
    let userType = document.getElementById("user-type").value;
    let userId = document.getElementById("user-id").value.trim();
    let password = document.getElementById("reg-password").value;
    let confirmPassword = document.getElementById("confirm-password").value;

    // Check empty fields
    if (fullname === "" || email === "" || userType === "" ||
        userId === "" || password === "" || confirmPassword === "") {

        alert("Please fill in all required fields.");
        return false;
    }

    // Check full name
    if (!/^[a-zA-Z ]+$/.test(fullname)) {
        alert("Please enter a valid full name.");
        return false;
    }

    // Check email format
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Check password length
    if (password.length < 8) {
        alert("Password must contain at least 8 characters.");
        return false;
    }

    // Check password confirmation
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    alert("Registration form validated successfully!");
    return true;
}
