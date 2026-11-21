<?php
session_start();
require_once "db.php";

$success = "";
$error = "";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userId = $_SESSION["user_id"];
    $itemName = trim($_POST["item_name"]);
    $category = $_POST["category"];
    $description = trim($_POST["description"]);
    $location = trim($_POST["location_lost"]);
    $itemDate = $_POST["date_lost"];
    $reportType = "Lost";
    $status = "Lost";
    $imageName = NULL;

    // Handle optional image upload
    if (!empty($_FILES["item_photo"]["name"])) {

        $uploadFolder = "images/items/";

        $fileName = time() . "_" . basename($_FILES["item_photo"]["name"]);
        $targetFile = $uploadFolder . $fileName;

        if (move_uploaded_file($_FILES["item_photo"]["tmp_name"], $targetFile)) {
            $imageName = $fileName;
        } else {
            $error = "Image upload failed.";
        }
    }

    // Save report into database
    if ($error == "") {

        $stmt = $conn->prepare(
            "INSERT INTO items (user_id, item_name, category, report_type, description, location, item_date, image, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issssssss",
            $userId,
            $itemName,
            $category,
            $reportType,
            $description,
            $location,
            $itemDate,
            $imageName,
            $status
        );

        if ($stmt->execute()) {
            $success = "Lost item report submitted successfully!";
        } else {
            $error = "Failed to submit report: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Report Lost Item</title>

    <!-- Connects this page to the main CSS file -->
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
                <a href="claim.php">Claims</a>
                <a href="admin-report-fake.php">Fake Claim Reports</a>

            <?php } else { ?>

                <!-- Student and Staff Navigation -->
                <a href="index.php">Home</a>
                <a href="browse.php">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="claim.php">Claims</a>

            <?php } ?>

        </nav>

        <?php if (isset($_SESSION["user_id"])) { ?>
            <a href="logout.php" class="login-button">Logout</a>
        <?php } else { ?>
           <a href="login.php" class="login-button">Login</a>
         <?php } ?>
    </header>


    <!-- Main Report Form -->
    <main class="report-container">

        <h2>Report a Lost Item</h2>

        <p class="report-description">
            Provide clear details about an item you lost.
        </p>
        <?php if ($success != "") { ?>
          <p style="color: green;"><?php echo $success; ?></p>
        <?php } ?>

        <?php if ($error != "") { ?>
          <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <div class="report-card">

            <h3>Lost Item Information</h3>

            <!-- Form for entering lost item details -->
            <!-- PHP will process and save this information later -->
            <form action="report-lost.php" method="POST" enctype="multipart/form-data">

                <div class="report-form-grid">

                    <!-- Item Name -->
                    <div class="report-group">
                        <label for="item-name">Item Name</label>
                        <input
                            type="text"
                            id="item-name"
                            name="item_name"
                            placeholder="e.g. Black wallet"
                            required
                        >
                    </div>

                    <!-- Item Category -->
                    <div class="report-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select item category</option>
                            <option value="Personal Belongings">Personal Belongings</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Documents">Documents</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <!-- Date the item was lost -->
                    <div class="report-group">
                        <label for="date-lost">Date Lost</label>
                        <input
                            type="date"
                            id="date-lost"
                            name="date_lost"
                            required
                        >
                    </div>

                    <!-- Location where item was lost -->
                    <div class="report-group">
                        <label for="floor">Floor / Level</label>
                        <select id="floor" required>
                            <option value="">Select floor / level</option>
                            <option value="Level 2">Level 2</option>
                            <option value="Level 3">Level 3</option>
                            <option value="Level 4">Level 4</option>
                            <option value="Level 5">Level 5</option>
                            <option value="Level 6">Level 6</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="report-group">
                        <label for="location-lost">Specific Location</label>
                        <select
                            id="location-lost"
                            name="location_lost"
                            required
                            disabled
                        >
                            <option value="">Select a floor first</option>
                        </select>
                    </div>

                </div>

                <!-- Item Description -->
                <div class="report-group full-width">
                    <label for="description">Item Description</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Describe identifying details..."
                        rows="5"
                        required
                    ></textarea>
                </div>

                <!-- Allows users to upload an item picture -->
                <div class="report-group full-width">
                    <label for="item-photo">Upload Item Photo (optional)</label>
                    <input
                        type="file"
                        id="item-photo"
                        name="item_photo"
                        accept="image/*"
                    >
                </div>

                <!-- Form buttons -->
                <div class="report-buttons">
                    <button type="submit" class="report-submit">
                        Submit Lost Report
                    </button>

                    <a href="index.php" class="report-cancel">
                        Cancel
                    </a>
                </div>

            </form>

        </div>

    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

    <script src="js/location-dropdown.js"></script>

</body>
</html>
