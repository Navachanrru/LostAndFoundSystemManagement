
function validateLoginForm() {

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;

    // Check empty email
    if (email === "") {
        alert("Please enter your email or admin username.");
        return false;
    }

    // Check empty password
    if (password === "") {
        alert("Please enter your password.");
        return false;
    }

    
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
    

    return true;
}

// Show or hide password
function togglePassword() {

            const passwordInput = document.getElementById("password");
            const toggleButton = document.querySelector(".toggle-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleButton.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                toggleButton.textContent = "Show";
            }
        }   
