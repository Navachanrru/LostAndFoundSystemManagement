<?php
session_start();

require_once "db.php";

// Retrieve all reported items from the database
$sql = "SELECT * FROM items ORDER BY item_id DESC";
$result = $conn->query($sql);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Lost & Found | Browse Items</title>

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

            <?php if (isset($_SESSION["user_id"]) || isset($_SESSION["admin_logged_in"])) { ?>
                <a href="logout.php" class="login-button">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="login-button">Login</a>
            <?php } ?>
        </header>


        <!-- Main Browse Items Section -->
        <main class="browse-container">

            <h2>Browse Lost & Found Items</h2>

            <p class="browse-description">
                Search reported items and check whether your belongings have been found.
            </p>


            <!-- Search and Filter Section -->
            <!-- Search functionality will be connected later -->
            <form class="search-filter" action="#" method="GET">

                <!-- Search by item name -->
                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    placeholder="Search by item name or keyword..."
                >

                <!-- Filter by category -->
                <select name="category" id="categoryFilter">
                    <option value="">All categories</option>
                    <option value="Personal Belongings">Personal Belongings</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Documents">Documents</option>
                    <option value="Others">Others</option>
                </select>

                <!-- Filter by report status -->
                <select name="status" id="statusFilter">
                    <option value="">Lost and Found</option>
                    <option value="Lost">Lost</option>
                    <option value="Found">Found</option>
                </select>

                <button type="button" class="search-button" onclick="searchItems()">
                    Search
                </button>

            </form>


            <!-- Reported Items -->
            <section class="browse-items">

                <h2>Reported Items</h2>

                <div class="browse-item-list">

                    <!-- Item 1: Black Wallet -->
                    <div class="browse-item-card" data-category="Personal Belongings">

                        <div class="browse-image">
                            <img src="images/items/wallet.jpg" alt="Black Wallet">
                        </div>

                        <div class="browse-item-info">
                            <h3>Black Wallet</h3>
                            <p>Level 3 Cubicle 5 • 2 Nov 2026</p>
                            <span class="status lost">Lost</span>
                        </div>

                        <a href="item-details.php?sample=1" class="view-details">
                            View Details
                        </a>

                    </div>


                    <!-- Item 2: Purple Mouse -->
                    <div class="browse-item-card" data-category="Electronics">

                        <div class="browse-image">
                            <img src="images/items/mouse.jpg" alt="Purple Mouse">
                        </div>

                        <div class="browse-item-info">
                            <h3>Purple Mouse</h3>
                            <p>Level 5 Room 501 • 3 Nov 2026</p>
                            <span class="status found">Found</span>
                        </div>

                        <a href="item-details.php?sample=2" class="view-details">
                            View Details
                        </a>

                    </div>


                    <!-- Item 3: Water Bottle -->
                    <div class="browse-item-card" data-category="Personal Belongings">

                        <div class="browse-image">
                            <img src="images/items/bottle.jpg" alt="Water Bottle">
                        </div>

                        <div class="browse-item-info">
                            <h3>Water Bottle</h3>
                            <p>College Canteen • 4 Nov 2026</p>
                            <span class="status found">Found</span>
                        </div>

                        <a href="item-details.php?sample=3" class="view-details">
                            View Details
                        </a>

                    </div>


                    <!-- Item 4: Macbook Charger -->
                    <div class="browse-item-card" data-category="Electronics">

                        <div class="browse-image">
                            <img src="images/items/macbook charger.jpg" alt="Macbook Charger">
                        </div>

                        <div class="browse-item-info">
                            <h3>Macbook Charger</h3>
                            <p>Study Area • 1 Nov 2026</p>
                            <span class="status lost">Lost</span>
                        </div>

                        <a href="item-details.php?sample=4" class="view-details">
                            View Details
                        </a>

                    </div>


                    <!-- Item 5: Red Umbrella -->
                    <div class="browse-item-card" data-category="Others">

                        <div class="browse-image">
                            <img src="images/items/red umbrella.jpg" alt="Red Umbrella">
                        </div>

                        <div class="browse-item-info">
                            <h3>Red Umbrella</h3>
                            <p>Main Entrance • 31 Oct 2026</p>
                            <span class="status found">Found</span>
                        </div>

                        <a href="item-details.php?sample=5" class="view-details">
                            View Details
                        </a>

                    </div>


                    <!-- Item 6: Pink Jacket -->
                    <div class="browse-item-card" data-category="Clothing">

                        <div class="browse-image">
                            <img src="images/items/pink jacket.jpg" alt="Pink Jacket">
                        </div>

                        <div class="browse-item-info">
                            <h3>Pink Jacket</h3>
                            <p>Network Lab • 30 Oct 2026</p>
                            <span class="status lost">Lost</span>
                        </div>

                        <a href="item-details.php?sample=6" class="view-details">
                            View Details
                        </a>

                    </div>


                    <?php if ($result && $result->num_rows > 0) { ?>

                        <?php while ($item = $result->fetch_assoc()) {

                            $reportType = strtolower($item["report_type"]);
                            $itemDate = date("d M Y", strtotime($item["item_date"]));

                        ?>

                            <div class="browse-item-card"
                                data-category="<?php echo htmlspecialchars($item["category"]); ?>">

                                <div class="browse-image">

                                    <?php if (!empty($item["image"])) { ?>

                                        <img src="images/items/<?php echo htmlspecialchars($item["image"]); ?>"
                                            alt="<?php echo htmlspecialchars($item["item_name"]); ?>">

                                    <?php } else { ?>

                                        <p>No image uploaded</p>

                                    <?php } ?>

                                </div>

                                <div class="browse-item-info">

                                    <h3><?php echo htmlspecialchars($item["item_name"]); ?></h3>

                                    <p>
                                        <?php echo htmlspecialchars($item["location"]); ?>
                                        •
                                        <?php echo $itemDate; ?>
                                    </p>

                                    <span class="status <?php echo htmlspecialchars($reportType); ?>">
                                        <?php echo htmlspecialchars($item["report_type"]); ?>
                                    </span>

                                </div>

                                <a href="item-details.php?id=<?php echo (int)$item["item_id"]; ?>" class="view-details">
                                    View Details
                                </a>

                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <p>No reported items found.</p>

                    <?php } ?>

                </div>

            </section>

        </main>


        <!-- Footer -->
        <footer class="footer">
            <p>Lost & Found System Management</p>
        </footer>

        <script src="js/search.js"></script>

    </body>
    </html>
