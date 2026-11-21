
<?php
session_start();
require_once "db.php";

// Check if user or admin is logged in
if (!isset($_SESSION["user_id"]) &&
    (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true)) {

    header("Location: login.php");
    exit();
}

// Admin can view all reports
if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {

    $result = $conn->query(
        "SELECT * FROM items ORDER BY item_id DESC"
    );

// Student and Staff can view only their own reports
} else {

    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare(
        "SELECT * FROM items WHERE user_id = ? ORDER BY item_id DESC"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
}
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Reports | Lost & Found</title>

        <!-- Link to CSS stylesheet -->
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
                    <a href="my-reports.php" class="active">Reports</a>
                    <a href="admin-dashboard.php">Dashboard</a>
                    <a href="claim.php">Claims</a>
                    <a href="admin-report-fake.php">Fake Claim Reports</a>

                <?php } else { ?>

                    <!-- Student and Staff Navigation -->
                    <a href="index.php">Home</a>
                    <a href="browse.php">Browse Items</a>
                    <a href="my-reports.php" class="active">My Reports</a>
                    <a href="claim.php">Claims</a>

                <?php } ?>

            </nav>

            <?php if (isset($_SESSION["user_id"]) || isset($_SESSION["admin_logged_in"])) { ?>
               <a href="logout.php" class="login-button">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="login-button">Login</a>
            <?php } ?>
        </header>

        <!-- Main Content -->
        <main class="container">

            <!-- Page Heading -->
            <section class="welcome">
                <h2>My Reports</h2>
                <p>View the lost and found reports you have submitted.</p>
            </section>

            <!-- My Submitted Reports Section -->
            <section class="my-reports">

                <div class="reports-heading">
                    <h3>My Submitted Reports</h3>

                    <a href="report-lost.php" class="primary-button">
                        New Report
                    </a>
                </div>

                <!-- Reports Table -->
                <div class="table-container">
                    <table class="reports-table">

                        <!-- Table Headings -->
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Report Type</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <!-- Table Records -->
                        <tbody>

                            <?php while ($row = $result->fetch_assoc()) { ?>

                                <tr>
                                    <td><?php echo htmlspecialchars($row["item_name"]); ?></td>

                                    <td><?php echo htmlspecialchars($row["report_type"]); ?></td>

                                    <td>
                                        <?php echo date("d M Y", strtotime($row["item_date"])); ?>
                                    </td>

                                    <td>
                                        <?php if ($row["status"] == "Found") { ?>

                                            <span class="report-status found-status">
                                                Found
                                            </span>

                                        <?php } else { ?>

                                            <span class="report-status pending">
                                                Lost
                                            </span>

                                        <?php } ?>
                                    </td>

                                    <td>
                                        <a href="item-details.php?id=<?php echo (int) $row["item_id"]; ?>">
                                            View
                                        </a>
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>

                <!-- Status Information -->
                <p class="report-note">
                    Status helps you track the progress of each report.
                </p>

            </section>

        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>Lost & Found System Management</p>
        </footer>

    </body>
    </html>
