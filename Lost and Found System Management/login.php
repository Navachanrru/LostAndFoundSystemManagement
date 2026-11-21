<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Temporary Admin Login
    if ($email == "admin" && $password == "Admin@123") {

        $_SESSION["admin_logged_in"] = true;
        $_SESSION["user_role"] = "admin";

        header("Location: admin-dashboard.php");
        exit();

    } else {

        // Check Student or Staff account in database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user["password"])) {

                // Check account status
                if ($user["status"] == "Active") {
                    // Clear any previous admin session
                    unset($_SESSION["admin_logged_in"]);

                    // Set normal user session
                    $_SESSION["user_id"] = $user["user_id"];
                    $_SESSION["user_name"] = $user["full_name"];
                    $_SESSION["user_role"] = $user["user_type"];

                    // Student and Staff temporarily go to Home page
                    header("Location: index.php");
                    exit();

                } else {
                    $error = "Your account is inactive.";
                }

            } else {
                $error = "Login details are incorrect.";
            }

        } else {
            $error = "Login details are incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Lost & Found</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <main class="login-page">

        <!-- Left welcome panel -->
        <section class="login-welcome">

            <a href="index.php" class="login-logo">Lost & Found</a>

            <div class="login-welcome-content">
                <span class="login-tag">COLLEGE LOST & FOUND</span>

                <h1>Welcome Back!</h1>

                <p>
                    Sign in to report lost items, discover found
                    belongings and manage your reports.
                </p>

                <div class="login-feature">
                    <span>01</span>
                    <p>Report lost or found belongings</p>
                </div>

                <div class="login-feature">
                    <span>02</span>
                    <p>Browse reported items</p>
                </div>

                <div class="login-feature">
                    <span>03</span>
                    <p>Track your claims and reports</p>
                </div>
            </div>

            <p class="login-side-footer">
                Helping our college community reunite with their belongings.
            </p>

        </section>

        <!-- Right login form -->
        <section class="login-form-area">

            <div class="login-card">

                <div class="login-icon">🔐</div>

                <h2>Login to Your Account</h2>

                <p class="login-description">
                    Enter your details to continue.
                </p>

                <!-- Display error message -->
                <?php if ($error != "") { ?>
                    <p class="login-error">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                <?php } ?>

                <form method="POST" action="" onsubmit="return validateLoginForm()">

                    <!-- Username / Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>

                        <input
                            type="text"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>

                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                onclick="togglePassword()"
                            >
                                Show
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-submit">
                        Login
                    </button>

                </form>

                <div class="register-link">
                    Don't have an account?
                    <a href="register.php">Register here</a>
                </div>

                <div class="register-link">
                    <a href="index.php">← Back to Home</a>
                </div>

            </div>

        </section>

    </main>

    <!-- Show or hide password -->

    <script src="js/login.js"></script>

</body>
</html>
