
<?php
session_start();

require_once "db.php";

// Check whether a normal user is logged in.
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] == "admin") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get the claim ID from the URL.
$claim_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Retrieve the selected claim and claimant information.
$stmt = $conn->prepare(
    "SELECT
        claims.claim_id,
        claims.item_id,
        claims.status,
        users.full_name,
        users.student_staff_id,
        items.item_name
     FROM claims
     INNER JOIN users ON claims.user_id = users.user_id
     INNER JOIN items ON claims.item_id = items.item_id
     WHERE claims.claim_id = ?"
);

$stmt->bind_param("i", $claim_id);
$stmt->execute();

$result = $stmt->get_result();
$claim = $result->fetch_assoc();

$stmt->close();

$message = "";

// Process the suspicious claim report.
if ($_SERVER["REQUEST_METHOD"] == "POST" && $claim) {

    // Prevent users from reporting their own claims.
    $stmt = $conn->prepare(
        "SELECT user_id FROM claims WHERE claim_id = ?"
    );

    $stmt->bind_param("i", $claim_id);
    $stmt->execute();

    $owner_result = $stmt->get_result();
    $claim_owner = $owner_result->fetch_assoc();

    $stmt->close();

    if ($claim_owner && $claim_owner["user_id"] == $user_id) {

        $message = "You cannot report your own claim.";

    } else {

        $report_reason = trim($_POST["report_reason"]);

        if (empty($report_reason)) {

            $message = "Please explain why you are reporting this claim.";

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO claim_reports
                (claim_id, reporter_id, report_reason)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "iis",
                $claim_id,
                $user_id,
                $report_reason
            );

            if ($stmt->execute()) {

                $stmt->close();

                header("Location: claim.php?report_success=1");
                exit();

            } else {

                $message = "Something went wrong. Please try again.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Report Suspicious Claim</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">

        <div class="logo">Lost & Found</div>

        <nav>
            <a href="index.php">Home</a>
            <a href="browse.php">Browse Items</a>
            <a href="my-reports.php">My Reports</a>
            <a href="claim.php" class="active">Claims</a>
        </nav>

        <a href="logout.php" class="login-button">Logout</a>

    </header>


    <!-- Report Suspicious Claim Section -->
    <main class="report-fake-container">

        <h2>Report Suspicious Claim</h2>

        <p>
            Submit a report if you believe someone has falsely claimed an item.
        </p>

        <?php if ($message != "") { ?>

            <div class="submit-claim-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <?php if ($claim) { ?>

            <div class="report-fake-card">

                <h3>Claim Information</h3>

                <p>
                    <strong>Item Name:</strong>
                    <?php echo htmlspecialchars($claim["item_name"]); ?>
                </p>

                <p>
                    <strong>Claimant Name:</strong>
                    <?php echo htmlspecialchars($claim["full_name"]); ?>
                </p>

                <p>
                    <strong>Student/Staff ID:</strong>
                    <?php echo htmlspecialchars($claim["student_staff_id"]); ?>
                </p>

                <p>
                    <strong>Claim Status:</strong>
                    <?php echo htmlspecialchars($claim["status"]); ?>
                </p>

            </div>

            <!-- Complaint Form -->
            <div class="report-card">

                <h3>Reason for Reporting</h3>

                <form method="POST" action="">

                    <div class="form-group">

                        <label for="report_reason">
                            Explain why you believe this claim is suspicious
                        </label>

                        <textarea
                            id="report_reason"
                            name="report_reason"
                            rows="5"
                            required
                            placeholder="Explain why you believe this person may not be the real owner..."
                        ></textarea>

                    </div>

                    <button type="submit" class="claim-button">
                        Submit Report
                    </button>

                    <a href="claim.php" class="back-button">
                        Cancel
                    </a>

                </form>

            </div>

        <?php } else { ?>

            <div class="report-card">

                <h3>Claim Not Found</h3>

                <p>The selected claim could not be found.</p>

                <a href="claim.php" class="back-button">
                    Back to Claims
                </a>

            </div>

        <?php } ?>


    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

</body>
</html>
