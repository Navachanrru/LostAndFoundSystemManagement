<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found | Home</title>
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

            <?php } elseif (isset($_SESSION["user_id"])) { ?>

                <!-- Student and Staff Navigation -->
                <a href="index.php" class="active">Home</a>
                <a href="browse.php">Browse Items</a>
                <a href="my-reports.php">My Reports</a>
                <a href="claim.php">Claims</a>

            <?php } else { ?>

                <!-- Guest Navigation -->
                <a href="index.php" class="active">Home</a>

            <?php } ?>

        </nav>

        <?php if (isset($_SESSION["user_id"]) || isset($_SESSION["admin_logged_in"])) { ?>

            <a href="logout.php" class="login-button">Logout</a>

        <?php } else { ?>

            <a href="login.php" class="login-button">Login</a>

        <?php } ?>
    </header>


    <main class="container">

        <!-- Welcome Section -->
        <section class="welcome">
            <h2>Welcome to Lost & Found</h2>
            <p>Find, report and reunite belongings across the college.</p>
        </section>

        <!-- Main Hero Section -->
        <section class="hero">

            <div class="hero-content">
                <span>COLLEGE LOST & FOUND</span>

                <h1>
                    Lost Something?<br>
                    Let's Help You Find It.
                </h1>

                <p>
                    A simple and convenient platform for students
                    and college staff to report and find belongings.
                </p>

             <div class="hero-buttons">
                <a href="report-lost.php" class="primary-button">
                  Report Lost Item
                </a>

                 <a href="report-found.php" class="secondary-button">
                  Report Found Item
                 </a>
              </div>
            </div>

            <!-- Visual Information Panel -->
            <div class="hero-visual">
                <div class="visual-icon">🔎</div>
                <h3>Let's Reunite Lost Belongings</h3>
                <p>Report an item, search for belongings and submit a claim.</p>

                <div class="visual-steps">
                    <div>
                        <span>01</span>
                        <p>Report an item</p>
                    </div>

                    <div>
                        <span>02</span>
                        <p>Search for items</p>
                    </div>

                    <div>
                        <span>03</span>
                        <p>Claim your item</p>
                    </div>
                </div>
            </div>

        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <h2>How It Works</h2>
            <p class="section-description">
                Three simple steps to help you find your belongings.
            </p>

            <div class="steps">

                <div class="step-card">
                    <span class="step-number">01</span>
                    <h3>Report</h3>
                    <p>Submit details of your lost or found item.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">02</span>
                    <h3>Search</h3>
                    <p>Browse reported items to find a match.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">03</span>
                    <h3>Claim</h3>
                    <p>Submit a request to claim your belongings.</p>
                </div>

            </div>
        </section>

        <!-- Recently Reported Items -->
        <section class="recent-items">

            <div class="section-heading">
                <div>
                    <h2>Recently Reported Items</h2>
                    <p class="section-description">
                        A preview of items reported around the college.
                    </p>
                </div>

                <a href="browse.php" class="browse-link">Browse Items →</a>
            </div>

            <div class="item-list">

                <!-- Black Wallet -->
                <div class="item-card">
                    <div class="item-image">
                        <img src="images/items/wallet.jpg" alt="Black Wallet">
                    </div>

                    <div class="item-info">
                        <h3>Black Wallet</h3>
                        <p>Level 3 Cubicle 5</p>
                        <small>2 Nov 2026</small>
                    </div>

                    <span class="status lost">Lost</span>
                </div>

                <!-- Water Bottle -->
                <div class="item-card">
                    <div class="item-image">
                        <img src="images/items/bottle.jpg" alt="Water Bottle">
                    </div>

                    <div class="item-info">
                        <h3>Water Bottle</h3>
                        <p>College Canteen</p>
                        <small>3 Nov 2026</small>
                    </div>

                    <span class="status found">Found</span>
                </div>

                <!-- AirPods Pro -->
                <div class="item-card">
                    <div class="item-image">
                        <img src="images/items/airpods.jpg" alt="AirPods Pro">
                    </div>

                    <div class="item-info">
                        <h3>AirPods Pro</h3>
                        <p>College Library</p>
                        <small>4 Nov 2026</small>
                    </div>

                    <span class="status found">Found</span>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Lost & Found System Management</p>
        <p>Helping the college community reunite with their belongings.</p>
    </footer>

</body>
</html>
