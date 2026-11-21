
<?php
session_start();
require_once "db.php";

// Check whether a normal user is logged in.
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] == "admin") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get the item ID from the URL.
$item_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$message = "";

// Retrieve the selected Found item.
$stmt = $conn->prepare(
    "SELECT * FROM items WHERE item_id = ? AND report_type = 'Found'"
);

$stmt->bind_param("i", $item_id);
$stmt->execute();

$result = $stmt->get_result();
$item = $result->fetch_assoc();

$stmt->close();

// Process the claim form.
if ($_SERVER["REQUEST_METHOD"] == "POST" && $item) {

    $claim_description = trim($_POST["claim_description"]);

    if (empty($claim_description)) {

        $message = "Please explain why you are claiming this item.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO claims (item_id, user_id, claim_description, status)
             VALUES (?, ?, ?, 'Pending')"
        );

        $stmt->bind_param(
            "iis",
            $item_id,
            $user_id,
            $claim_description
        );

        if ($stmt->execute()) {
            header("Location: claim.php?success=1");
            exit();
        } else {
            $message = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Submit Claim</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="navbar">
        <div class="logo">Lost & Found</div>

        <nav>
            <a href="index.php">Home</a>
            <a href="browse.php">Browse Items</a>
            <a href="my-reports.php">My Reports</a>
            <a href="claim.php">Claims</a>
        </nav>

        <a href="logout.php" class="login-button">Logout</a>
    </header>

    <main class="submit-claim-container">

        <h2>Submit Item Claim</h2>

        <p>Please provide information to help verify your ownership.</p>

        <?php if ($message != "") { ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <?php if ($item) { ?>

            <div class="submit-claim-card">

                <h3>
                    <?php echo htmlspecialchars($item["item_name"]); ?>
                </h3>

                <p>
                    Category:
                    <?php echo htmlspecialchars($item["category"]); ?>
                </p>

                <p>
                    Location:
                    <?php echo htmlspecialchars($item["location"]); ?>
                </p>

                <p>
                    Description:
                    <?php echo htmlspecialchars($item["description"]); ?>
                </p>

                <form method="POST" action="">

                    <div class="form-group">

                        <label for="claim_description">
                            Explain why this item belongs to you
                        </label>

                        <textarea
                            id="claim_description"
                            name="claim_description"
                            rows="5"
                            required
                            placeholder="Describe the item and provide details that can help verify your ownership..."
                        ></textarea>

                    </div>

                    <div class="submit-claim-buttons">
                        <button type="submit" class="claim-button">
                            Submit Claim
                        </button>

                        <a href="browse.php" class="back-button">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        <?php } else { ?>

            <div class="report-card">

                <h3>Item Not Available</h3>

                <p>
                    This item does not exist or is not listed as Found.
                </p>

                <a href="browse.php" class="back-button">
                    Back to Items
                </a>

            </div>

        <?php } ?>

    </main>

    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

</body>
</html>
