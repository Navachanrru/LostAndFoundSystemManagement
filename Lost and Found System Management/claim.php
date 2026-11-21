
<?php
session_start();

require_once "db.php";

// Allow only admin to update claim status and remarks.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
        header("Location: claim.php");
        exit();
    }

    $claim_id = (int) $_POST["claim_id"];
    $new_status = $_POST["new_status"];
    $admin_remarks = trim($_POST["admin_remarks"] ?? "");

    if (($new_status == "Approved" || $new_status == "Rejected")
        && !empty($admin_remarks)) {

        $stmt = $conn->prepare(
            "UPDATE claims
             SET status = ?, admin_remarks = ?
             WHERE claim_id = ?"
        );

        $stmt->bind_param("ssi", $new_status, $admin_remarks, $claim_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: claim.php");
    exit();
}

// Check whether a user or admin is logged in.
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

// Retrieve all submitted claims with claimant and item information.
$sql = "SELECT
            claims.claim_id,
            claims.item_id,
            claims.claim_description,
            claims.claim_date,
            claims.status,
            claims.admin_remarks,
            users.full_name,
            users.student_staff_id,
            items.item_name,
            items.image,
            items.location
        FROM claims
        INNER JOIN users ON claims.user_id = users.user_id
        INNER JOIN items ON claims.item_id = items.item_id
        ORDER BY claims.claim_date DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Claims</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">

        <div class="logo">Lost & Found</div>

        <nav>

            <?php if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) { ?>

                <!-- Admin Navigation -->
                <a href="browse.php">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="admin-dashboard.php">Dashboard</a>
                <a href="claim.php" class="active">Claims</a>
                <a href="admin-report-fake.php">Fake Claim Reports</a>

            <?php } else { ?>

                <!-- Student and Staff Navigation -->
                <a href="index.php">Home</a>
                <a href="browse.php">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="claim.php" class="active">Claims</a>

            <?php } ?>

        </nav>

        <a href="logout.php" class="login-button">Logout</a>

    </header>


    <!-- Main Claims Section -->
    <main class="container">

        <h2>Submitted Claims</h2>

        <?php if (isset($_GET["report_success"])) { ?>

            <div class="submit-claim-message">
                Your report has been submitted successfully. The admin will review it.
            </div>

        <?php } ?>

        <p>
            View submitted claims and check the claimant's information.
        </p>


        <!-- Claims will be displayed here in the next step -->
         <?php if ($result && $result->num_rows > 0) { ?>

            <div class="reports-table">

                <table>

                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Claimant Name</th>
                            <th>Student/Staff ID</th>
                            <th>Claim Explanation</th>
                            <th>Claim Date</th>
                            <th>Status</th>
                            <th>Admin Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($claim = $result->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($claim["item_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($claim["full_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($claim["student_staff_id"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($claim["claim_description"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($claim["claim_date"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($claim["status"]); ?>
                                </td>

                                <td>
                                    <?php if (isset($_SESSION["admin_logged_in"]) &&
                                            $_SESSION["admin_logged_in"] === true &&
                                            $claim["status"] == "Pending") { ?>

                                        <textarea
                                            name="admin_remarks"
                                            form="claim-form-<?php echo $claim['claim_id']; ?>"
                                            placeholder="Enter admin remarks..."
                                            rows="3"
                                            required></textarea>

                                    <?php } else { ?>

                                        <?php
                                        echo !empty($claim["admin_remarks"])
                                            ? htmlspecialchars($claim["admin_remarks"])
                                            : "No remarks yet";
                                        ?>

                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if (isset($_SESSION["admin_logged_in"]) &&
                                            $_SESSION["admin_logged_in"] === true) { ?>

                                        <?php if ($claim["status"] == "Pending") { ?>

                                            <form method="POST" action="claim.php"
                                                id="claim-form-<?php echo $claim['claim_id']; ?>">

                                                <input type="hidden" name="claim_id"
                                                    value="<?php echo $claim['claim_id']; ?>">

                                                <button type="submit" name="new_status"
                                                    value="Approved" class="primary-button">
                                                    Approve
                                                </button>

                                                <button type="submit" name="new_status"
                                                    value="Rejected" class="claim-button">
                                                    Reject
                                                </button>

                                            </form>

                                        <?php } else { ?>

                                            No Action Required

                                        <?php } ?>

                                    <?php } else { ?>

                                        <a href="report-fake.php?id=<?php echo $claim['claim_id']; ?>"
                                            class="claim-button">
                                            Report
                                        </a>

                                    <?php } ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        <?php } else { ?>

            <div class="report-card">
                <h3>No Claims Available</h3>
                <p>There are currently no submitted claims.</p>
            </div>

        <?php } ?>


    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

</body>
</html>