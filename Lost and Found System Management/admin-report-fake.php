<?php
session_start();

require_once "db.php";

// Check whether the admin is logged in.
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

// Delete resolved fake claim reports.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_report_id"])) {

    $report_id = (int) $_POST["delete_report_id"];

    $stmt = $conn->prepare(
        "DELETE FROM claim_reports
         WHERE report_id = ?
         AND status = 'Resolved'"
    );

    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin-report-fake.php?deleted=1");
    exit();
}

// Process admin complaint status update.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $report_id = (int) $_POST["report_id"];
    $status = $_POST["status"];

    // Allow only these complaint statuses.
    $allowed_statuses = ["Pending", "Reviewed", "Resolved"];

    if (in_array($status, $allowed_statuses)) {

        $stmt = $conn->prepare(
            "UPDATE claim_reports SET status = ? WHERE report_id = ?"
        );

        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            $stmt->close();

            header("Location: admin-report-fake.php?updated=1");
            exit();
        }

        $stmt->close();
    }
}

// Retrieve all suspicious claim reports.
$sql = "SELECT
            claim_reports.report_id,
            claim_reports.claim_id,
            claim_reports.reporter_id,
            claim_reports.report_reason,
            claim_reports.report_date,
            claim_reports.status,
            reporter.full_name AS reporter_name,
            reporter.student_staff_id AS reporter_student_id,
            claimant.full_name AS claimant_name,
            claimant.student_staff_id AS claimant_student_id,
            items.item_name
        FROM claim_reports
        INNER JOIN users AS reporter
            ON claim_reports.reporter_id = reporter.user_id
        INNER JOIN claims
            ON claim_reports.claim_id = claims.claim_id
        INNER JOIN users AS claimant
            ON claims.user_id = claimant.user_id
        INNER JOIN items
            ON claims.item_id = items.item_id
        ORDER BY claim_reports.report_date DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Suspicious Claim Reports</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">

        <div class="logo">Lost & Found</div>

        <nav>
            <a href="browse.php">Browse Items</a>
            <a href="my-reports.php">My Reports</a>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="claim.php">Claims</a>
            <a href="admin-report-fake.php" class="active">Fake Claim Reports</a>
        </nav>

        <a href="logout.php" class="login-button">Logout</a>

    </header>


    <!-- Main Content -->
    <main class="container">

        <h2>Suspicious Claim Reports</h2>

        <p>Review complaints submitted by users about suspicious item claims.</p>


        <!-- Report table will be added here -->
         <?php if ($result && $result->num_rows > 0) { ?>

            <div class="reports-table">

                <table>

                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Reported Claimant</th>
                            <th>Claimant ID</th>
                            <th>Reported By</th>
                            <th>Reporter ID</th>
                            <th>Reason</th>
                            <th>Report Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($report = $result->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($report["item_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["claimant_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["claimant_student_id"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["reporter_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["reporter_student_id"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["report_reason"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($report["report_date"]); ?>
                                </td>

                                <td>
                                     <form method="POST" action="" class="admin-report-status-form">

                                        <input
                                            type="hidden"
                                            name="report_id"
                                            value="<?php echo $report['report_id']; ?>"
                                        >

                                        <select name="status">

                                            <option value="Pending"
                                                <?php if ($report["status"] == "Pending") echo "selected"; ?>>
                                                Pending
                                            </option>

                                            <option value="Reviewed"
                                                <?php if ($report["status"] == "Reviewed") echo "selected"; ?>>
                                                Reviewed
                                            </option>

                                            <option value="Resolved"
                                                <?php if ($report["status"] == "Resolved") echo "selected"; ?>>
                                                Resolved
                                            </option>

                                        </select>

                                        <button type="submit" class="claim-button">
                                            Update
                                        </button>

                                    </form>        
                                </td>

                                <td>

                                    <?php if ($report["status"] == "Resolved") { ?>

                                        <form method="POST" action="" onsubmit="return confirmDelete();">
                                            
                                            <input type="hidden" name="delete_report_id"
                                                value="<?php echo $report['report_id']; ?>">

                                            <button type="submit" class="claim-button">
                                                Delete
                                            </button>

                                        </form>

                                    <?php } else { ?>

                                        No Action

                                    <?php } ?>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        <?php } else { ?>

            <div class="report-card">

                <h3>No Suspicious Reports</h3>

                <p>There are currently no suspicious claim reports submitted.</p>

            </div>

        <?php } ?>


    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

    <script src="js/delete.js"></script>

</body>
</html>