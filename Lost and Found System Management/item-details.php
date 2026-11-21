
<?php
session_start();
require_once "db.php";

// Original sample items
$items = [
    1 => [
        "name" => "Black Wallet",
        "image" => "wallet.jpg",
        "status" => "Lost",
        "category" => "Personal Belongings",
        "date" => "2 November 2026",
        "location" => "College Library",
        "description" => "Black leather wallet with a card holder."
    ],

    2 => [
        "name" => "Purple Mouse",
        "image" => "mouse.jpg",
        "status" => "Found",
        "category" => "Electronics",
        "date" => "3 November 2026",
        "location" => "Level 5 Room 501",
        "description" => "Purple wireless computer mouse."
    ],

    3 => [
        "name" => "Water Bottle",
        "image" => "bottle.jpg",
        "status" => "Found",
        "category" => "Personal Belongings",
        "date" => "4 November 2026",
        "location" => "College Canteen",
        "description" => "Blue reusable water bottle."
    ],

    4 => [
        "name" => "Macbook Charger",
        "image" => "macbook charger.jpg",
        "status" => "Lost",
        "category" => "Electronics",
        "date" => "1 November 2026",
        "location" => "Study Area",
        "description" => "White MacBook charging adapter and cable."
    ],

    5 => [
        "name" => "Red Umbrella",
        "image" => "red umbrella.jpg",
        "status" => "Found",
        "category" => "Personal Belongings",
        "date" => "31 October 2026",
        "location" => "Main Entrance",
        "description" => "Red folding umbrella."
    ],

    6 => [
        "name" => "Pink Jacket",
        "image" => "pink jacket.jpg",
        "status" => "Lost",
        "category" => "Clothing",
        "date" => "30 October 2026",
        "location" => "Network Lab",
        "description" => "Pink hooded jacket."
    ]
];

// Read the item ID from the URL
$item_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Check whether this is an original sample item
if (isset($_GET['sample']) && isset($items[(int) $_GET['sample']])) {

    $item = $items[(int) $_GET['sample']];

} else {

    // Retrieve the actual item from MySQL
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();

        $item = [
            "name" => $row["item_name"],
            "image" => $row["image"],
            "status" => $row["report_type"],
            "category" => $row["category"],
            "date" => date("d F Y", strtotime($row["item_date"])),
            "location" => $row["location"],
            "description" => $row["description"]
        ];

    } else {

        $item = null;

    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found | Item Details</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo">Lost & Found</div>

        <nav>

            <?php if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) { ?>

                <!-- Admin Navigation -->
                <a href="browse.php" class="active">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="admin-dashboard.php">Dashboard</a>
                <a href="claim.php">Claims</a>
                <a href="admin-report-fake.php">Fake Claim Reports</a>

            <?php } else { ?>

                <!-- Student and Staff Navigation -->
                <a href="index.php">Home</a>
                <a href="browse.php" class="active">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="claim.php">Claims</a>

            <?php } ?>

        </nav>

        <a href="login.php" class="login-button">Login</a>
    </header>


    <main class="details-container">

        <h2>Item Details</h2>

        <p class="details-description">
            Review the item information before submitting a claim.
        </p>

        <?php if ($item !== null): ?>

            <!-- Display the selected item's information -->
            <div class="details-card">

                <div class="details-image">
                    <img
                        src="images/items/<?php echo htmlspecialchars($item['image']); ?>"
                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                    >
                </div>

                <div class="details-info">

                    <h2>
                        <?php echo htmlspecialchars($item['name']); ?>
                    </h2>

                    <!-- Displays Lost or Found status -->
                    <span class="status <?php echo strtolower($item['status']); ?>">
                        <?php echo htmlspecialchars($item['status']); ?>
                    </span>

                    <div class="detail-row">
                        <strong>Category</strong>
                        <span><?php echo htmlspecialchars($item['category']); ?></span>
                    </div>

                    <div class="detail-row">
                        <strong>Date reported</strong>
                        <span><?php echo htmlspecialchars($item['date']); ?></span>
                    </div>

                    <div class="detail-row">
                        <strong>Location</strong>
                        <span><?php echo htmlspecialchars($item['location']); ?></span>
                    </div>

                    <div class="detail-row">
                        <strong>Description</strong>
                        <span><?php echo htmlspecialchars($item['description']); ?></span>
                    </div>

                    <div class="details-buttons">

                        <?php if ($item["status"] == "Found") { ?>

                            <a href="submit-claim.php?id=<?php echo isset($_GET['sample']) ? (int) $_GET['sample'] : $item_id; ?>" class="claim-button">
                                Submit Claim
                            </a>

                        <?php } ?>

                        <a href="browse.php" class="back-button">
                            Back to Items
                        </a>

                    </div>

                </div>

                <p class="details-note">
                    The administrator may ask you to verify ownership
                    before returning an item.
                </p>

            </div>

        <?php else: ?>

            <!-- Message shown if the item ID is invalid -->
            <div class="report-card">
                <h3>Item Not Found</h3>
                <p>Please return to the Browse Items page.</p>
                <a href="browse.php" class="back-button">Back to Items</a>
            </div>

        <?php endif; ?>

    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
    </footer>

</body>
</html>
