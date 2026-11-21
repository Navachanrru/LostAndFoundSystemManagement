<?php
require_once "db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $userType = $_POST["user_type"];
    $userId = trim($_POST["user_id"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $status = "Active";

            // Insert new account into database
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, email, password, user_type, student_staff_id, status)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $fullname,
                $email,
                $hashedPassword,
                $userType,
                $userId,
                $status
            );

            if ($stmt->execute()) {
                $success = "Account created successfully! You can now login.";
            } else {
                $error = "Registration failed: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Register</title>

    <!-- Connects this page to the main CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">

        <div class="logo">Lost & Found</div>

        <!-- Navigation links -->
        <nav>
            <a href="index.php">Home</a>
            <a href="browse.php">Browse Items</a>
            <a href="my-reports.php">My Reports</a>
        </nav>

        <a href="login.php" class="login-button">Login</a>

    </header>


    <!-- Registration Page Content -->
    <main class="register-container">

        <div class="register-card">

            <!-- Registration Heading -->
            <h2>Create an Account</h2>

            <p class="register-description">
                Register as a student or college staff member
            </p>
            <?php if ($success != "") { ?>
               <p style="color: green;"><?php echo $success; ?></p>
            <?php } ?>

            <?php if ($error != "") { ?>
               <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>


            <!-- Registration Form -->
            <!-- Form processing will be connected using PHP later -->
            <form action="register.php" method="POST" onsubmit="return validateRegisterForm()">

                <div class="register-form-grid">

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="fullname">Full Name</label>

                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            placeholder="Enter your full name"
                            required
                        >
                    </div>


                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="reg-email">Email Address</label>

                        <input
                            type="email"
                            id="reg-email"
                            name="email"
                            placeholder="Enter your college email"
                            required
                        >
                    </div>


                    <!-- User Type -->
                    <div class="form-group">
                        <label for="user-type">User Type</label>

                        <!-- Dropdown for selecting student or staff -->
                        <select id="user-type" name="user_type" required>
                            <option value="">Select student or staff</option>
                            <option value="Student">Student</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>


                    <!-- Student or Staff ID -->
                    <div class="form-group">
                        <label for="user-id">Student / Staff ID</label>

                        <input
                            type="text"
                            id="user-id"
                            name="user_id"
                            placeholder="Enter your ID"
                            required
                        >
                    </div>


                    <!-- Password -->
                    <div class="form-group">
                        <label for="reg-password">Password</label>

                        <input
                            type="password"
                            id="reg-password"
                            name="password"
                            placeholder="Create a password"
                            required
                        >
                    </div>


                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>

                        <input
                            type="password"
                            id="confirm-password"
                            name="confirm_password"
                            placeholder="Re-enter your password"
                            required
                        >
                    </div>

                </div>


                <!-- Submit Registration Form -->
                <button type="submit" class="register-submit">
                    Create Account
                </button>

            </form>


            <!-- Link to Login Page -->
            <p class="login-link">
                Already have an account?
                <a href="login.php">Login</a>
            </p>

        </div>

    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

    <script src="js/register.js"></script>

</body>
</html>
