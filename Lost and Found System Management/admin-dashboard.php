<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

// Update user account status
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = (int) $_POST["user_id"];
    $new_status = $_POST["new_status"];

    if ($new_status == "active" || $new_status == "inactive") {

        $stmt = $conn->prepare(
            "UPDATE users SET status = ? WHERE user_id = ? AND LOWER(user_type) != 'admin'"
        );

        $stmt->bind_param("si", $new_status, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin-dashboard.php");
    exit();
}

// Retrieve all user accounts
$user_result = $conn->query(
    "SELECT user_id, full_name, email, user_type, status
     FROM users
     ORDER BY full_name ASC"
);

// Retrieve the latest reports
$report_result = $conn->query(
    "SELECT item_id, item_name, report_type, item_date, status
     FROM items
     ORDER BY item_id DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Lost & Found</title>

    <!-- Link to CSS stylesheet -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo">Lost & Found</div>

        <nav>
            <a href="browse.php">Browse Items</a>
            <a href="my-reports.php">My Reports</a>
            <a href="admin-dashboard.php" class="active">Dashboard</a>
            <a href="claim.php">Claims</a>
            <a href="admin-report-fake.php">Fake Claim Reports</a>
        </nav>

        <a href="login.php" class="login-button">Logout</a>
    </header>

    <!-- Main Dashboard Content -->
    <main class="container">

        <!-- Page Heading -->
        <section class="welcome">
            <h2>Admin Dashboard</h2>
            <p>Manage lost and found reports in the system.</p>
        </section>

        <!-- Dashboard Summary Cards -->
        <section class="dashboard-summary">

            <div class="summary-card">
                <h3>Total Items</h3>
                <p class="summary-number">24</p>
            </div>

            <div class="summary-card">
                <h3>Lost Items</h3>
                <p class="summary-number">10</p>
            </div>

            <div class="summary-card">
                <h3>Found Items</h3>
                <p class="summary-number">14</p>
            </div>

            <div class="summary-card">
                <h3>Pending Claims</h3>
                <p class="summary-number">5</p>
            </div>

        </section>

        <!-- User Status Management Section -->
        <section class="admin-reports">
            <div class="admin-section-heading">
                <h3>User Accounts & Graduation Status</h3>
            </div>

            <div class="table-container">
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($user_result && $user_result->num_rows > 0) { ?>

                            <?php while ($row = $user_result->fetch_assoc()) { ?>

                                <tr>
                                    <td><?php echo htmlspecialchars($row["full_name"]); ?></td>

                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>

                                    <td><?php echo htmlspecialchars($row["user_type"]); ?></td>

                                    <td>
                                        <?php if (strtolower($row["status"]) == "inactive") { ?>

                                            <span class="report-status" style="background-color: #e74c3c; color: white;">
                                                Inactive
                                            </span>

                                        <?php } else { ?>

                                            <span class="report-status" style="background-color: #2ecc71; color: white;">
                                                Active
                                            </span>

                                        <?php } ?>
                                    </td>

                                    <td>

                                        <?php if (strtolower($row["user_type"]) != "admin") { ?>

                                            <form method="POST" action="admin-dashboard.php">

                                                <input type="hidden" name="user_id"
                                                    value="<?php echo $row["user_id"]; ?>">

                                                <?php if (strtolower($row["status"]) == "inactive") { ?>

                                                    <input type="hidden" name="new_status" value="active">

                                                    <button type="submit" class="primary-button">
                                                        Activate
                                                    </button>

                                                <?php } else { ?>

                                                    <input type="hidden" name="new_status" value="inactive">

                                                    <button type="submit" class="primary-button">
                                                        Deactivate
                                                    </button>

                                                <?php } ?>

                                            </form>

                                        <?php } else { ?>

                                            Admin Account

                                        <?php } ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4">No users found.</td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>
            </div>
        </section>

        <!-- Recent Reports Section -->
        <section class="admin-reports">

            <div class="admin-section-heading">
                <h3>Recent Reports</h3>
            </div>

            <div class="table-container">
                <table class="reports-table">

                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Report Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($report_result && $report_result->num_rows > 0) { ?>

                            <?php while ($row = $report_result->fetch_assoc()) { ?>

                                <tr>
                                    <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["report_type"]); ?></td>
                                    <td><?php echo date("d M Y", strtotime($row["item_date"])); ?></td>
                                    <td>
                                        <?php if ($row["status"] == "Found") { ?>
                                            <span class="report-status found-status">Found</span>
                                        <?php } else { ?>
                                            <span class="report-status pending">
                                                <?php echo htmlspecialchars($row["status"]); ?>
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="item-details.php?id=<?php echo (int) $row["item_id"]; ?>">View</a>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php } else { ?>
                            <tr>
                                <td colspan="5">No reports found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>

        </section>

        <!-- Management Sections -->
        <section class="admin-management">

            <div class="management-card">
                <h3>Manage Items</h3>
                <p>View and manage all lost and found item reports.</p>
                <a href="browse.php" class="primary-button">
                    View Items
                </a>
            </div>

            <div class="management-card">
                <h3>Manage Claims</h3>
                <p>Review and manage claims submitted by users.</p>
                <a href="my-reports.php" class="primary-button">
                    View Reports
                </a>
            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

</body>
</html>