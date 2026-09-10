<?php

session_start();

date_default_timezone_set("Africa/Cairo");

require_once "../php/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
   SELECT id, name, email, profile_image
FROM users
WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    session_destroy();
    header("Location: login.html");
    exit;
}

$user = $result->fetch_assoc();

$stmt->close();

$user_name = $user["name"];
$user_email = $user["email"];

// Get babies for current user
$stmt = $conn->prepare("
    SELECT id, name, birth_date, gender
    FROM babies
    WHERE user_id = ?
    ORDER BY id ASC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$babies_result = $stmt->get_result();

$babies = [];

while ($baby = $babies_result->fetch_assoc()) {
    $babies[] = $baby;
}

$stmt->close();

// Select baby from session
$selected_baby = null;

if (!empty($_SESSION["selected_baby_id"])) {
    foreach ($babies as $baby) {
        if ((int)$baby["id"] === (int)$_SESSION["selected_baby_id"]) {
            $selected_baby = $baby;
            break;
        }
    }
}

// If no valid baby is selected, select the first baby
if (!$selected_baby && !empty($babies)) {
    $selected_baby = $babies[0];
    $_SESSION["selected_baby_id"] = $selected_baby["id"];
}
// Get today's date
$today = date("Y-m-d");

// Default summary values
$sleep_count = 0;
$feeding_count = 0;
$diaper_count = 0;
$notes_count = 0;

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    // Today's records count
    $stmt = $conn->prepare("
        SELECT record_type, COUNT(*) AS total
        FROM daily_records
        WHERE baby_id = ?
        AND DATE(created_at) = ?
        GROUP BY record_type
    ");

    $stmt->bind_param("is", $baby_id, $today);
    $stmt->execute();

    $summary_result = $stmt->get_result();

    while ($row = $summary_result->fetch_assoc()) {

        if ($row["record_type"] === "Sleep") {
            $sleep_count = $row["total"];
        }

        if ($row["record_type"] === "Feeding") {
            $feeding_count = $row["total"];
        }

        if ($row["record_type"] === "Diaper") {
            $diaper_count = $row["total"];
        }

        if ($row["record_type"] === "Notes") {
            $notes_count = $row["total"];
        }
    }

    $stmt->close();
}

// Calculate baby age
$baby_age = "";

if ($selected_baby) {
    $birth_date = new DateTime($selected_baby["birth_date"]);
    $today = new DateTime();

    $age = $birth_date->diff($today);

    if ($age->y > 0) {
        $baby_age = $age->y . " Year";
        if ($age->y > 1) {
            $baby_age .= "s";
        }

        if ($age->m > 0) {
            $baby_age .= " " . $age->m . " Month";
            if ($age->m > 1) {
                $baby_age .= "s";
            }
        }
    } else {
        $baby_age = $age->m . " Month";
        if ($age->m != 1) {
            $baby_age .= "s";
        }
    }
}

// Get recent activities
$recent_activities = [];

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT id, record_type, details, start_time, end_time, notes, created_at
        FROM daily_records
        WHERE baby_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");

    $stmt->bind_param("i", $baby_id);
    $stmt->execute();

    $recent_result = $stmt->get_result();

    while ($activity = $recent_result->fetch_assoc()) {
        $recent_activities[] = $activity;
    }

    $stmt->close();


}

// Get upcoming vaccination
$upcoming_vaccine = null;

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT bv.due_date, v.name
        FROM baby_vaccinations bv
        INNER JOIN vaccines v ON bv.vaccine_id = v.id
        WHERE bv.baby_id = ?
        AND bv.date_taken IS NULL
        AND bv.due_date >= CURDATE()
        ORDER BY bv.due_date ASC
        LIMIT 1
    ");

    $stmt->bind_param("i", $baby_id);
    $stmt->execute();

    $vaccine_result = $stmt->get_result();

    $upcoming_vaccine = $vaccine_result->fetch_assoc();

    $stmt->close();
}

// Get suggested food
$suggested_food = null;

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT f.id, f.name
        FROM foods f
        WHERE NOT EXISTS (
            SELECT 1
            FROM baby_meals bm
            WHERE bm.food_id = f.id
            AND bm.baby_id = ?
        )
        ORDER BY f.id ASC
        LIMIT 1
    ");

    $stmt->bind_param("i", $baby_id);
    $stmt->execute();

    $food_result = $stmt->get_result();

    $suggested_food = $food_result->fetch_assoc();

    $stmt->close();
}



?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | BabyCare</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Main Website CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="dashboard-page">

    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="dashboard-sidebar">

        <!-- Logo -->
      <div class="sidebar-logo">
          <a href="dashboard.php">
          <img src="../assets/icons/logo.png" alt="BabyCare">
          </a>
        </div>


        <!-- Navigation -->
        <nav class="sidebar-nav">

            <a href="dashboard.php" class="sidebar-link active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="profile.php" class="sidebar-link">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>

            <a href="baby_profile.php" class="sidebar-link">
                <i class="bi bi-people"></i>
                <span>Baby Profile</span>
            </a>

            <a href="foods.php" class="sidebar-link">
                <i class="bi bi-egg-fried"></i>
                <span>Foods</span>
            </a>

            <a href="vaccination.php" class="sidebar-link">
                <i class="bi bi-bandaid"></i>
                <span>Vaccination</span>
            </a>

            <a href="daily-tracker.php" class="sidebar-link">
                <i class="bi bi-calendar-check"></i>
                <span>Daily Tracker</span>
            </a>

            <a href="tips_articles.php" class="sidebar-link">
                <i class="bi bi-journal-text"></i>
                <span>Tips & Articles</span>
            </a>

            <a href="settings.php" class="sidebar-link">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>

        </nav>


       <!-- Logout -->
<div class="sidebar-bottom">

    <a href="../php/auth/logout.php" class="sidebar-link logout-link">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
    </a>

</div>
        </div>

    </aside>



    <!-- =========================
         MAIN
    ========================== -->

    <main class="dashboard-main">


        <!-- =========================
             TOP BAR
        ========================== -->

        <header class="dashboard-topbar">

            <!-- Search -->
            <div class="dashboard-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search anything..."
                >

            </div>


            <!-- Right Actions -->
            <div class="dashboard-top-actions">


                <!-- Notifications -->
                <div class="dropdown notification-wrapper">

                    <button
                        class="notification-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <i class="bi bi-bell"></i>

                        <span class="notification-count">
                        
                        </span>

                    </button>


                    <div class="dropdown-menu dropdown-menu-end notification-menu">


                        <div class="notification-header">

                            <strong>Notifications</strong>

                            <button type="button">
                                Mark all as read
                            </button>

                        </div>


                        <!-- Notification 1 -->
                        <div class="notification-item">

                            <div class="notification-icon pink">
                                <i class="bi bi-bandaid"></i>
                            </div>

                            <div>
                                <h6>Upcoming Vaccination</h6>

                                <p>
                                    <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "your baby" ?> has a vaccination coming up.
                                </p>

                                <small>
                                    Today
                                </small>
                            </div>

                        </div>


                        <!-- Notification 2 -->
                        <div class="notification-item">

                            <div class="notification-icon yellow">
                                <i class="bi bi-egg-fried"></i>
                            </div>

                            <div>
                                <h6>Try New Food</h6>

                                <p>
                                    It's a good time to introduce a new food.
                                </p>

                                <small>
                                    2 hours ago
                                </small>
                            </div>

                        </div>


                        <!-- Notification 3 -->
                        <div class="notification-item">

                            <div class="notification-icon blue">
                                <i class="bi bi-lightbulb"></i>
                            </div>

                            <div>
                                <h6>New Tip</h6>

                                <p>
                                    A new baby care tip is waiting for you.
                                </p>

                                <small>
                                    Yesterday
                                </small>
                            </div>

                        </div>

                    </div>

                </div>



                <!-- =========================
                     USER ACCOUNT
                ========================== -->

                <div class="dropdown user-wrapper">

                    <button
                        class="user-account-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                       <div class="user-avatar">
    <?php if (!empty($user["profile_image"])): ?>
        <img
            src="../<?= htmlspecialchars($user["profile_image"]) ?>"
            alt="Profile"
        >
    <?php else: ?>
        <?= strtoupper(substr($user_name, 0, 1)) ?>
    <?php endif; ?>
</div>

                        <div class="user-info">

                            <strong><?= htmlspecialchars($user_name) ?></strong>

                            <span>Parent</span>

                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </button>


                    <!-- Account Dropdown -->

                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">

                        <li class="user-dropdown-name">

                            <strong><?= htmlspecialchars($user_name) ?></strong>

                            <span><?= htmlspecialchars($user_email) ?></span>

                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="profile.php"
                            >
                                <i class="bi bi-person"></i>
                                My Profile
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item"
                                href="settings.php"
                            >
                                <i class="bi bi-gear"></i>
                                Settings
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                class="dropdown-item logout-dropdown"
                                href="login.html"
                            >
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

        </header>



        <!-- =========================
             DASHBOARD CONTENT
        ========================== -->

        <div class="dashboard-content">


            <!-- =========================
                 WELCOME
            ========================== -->

            <section class="welcome-card">

                <div class="welcome-text">

                    <span class="section-label">
                        TODAY'S OVERVIEW
                    </span>

                    <h1>
                        Welcome, <span><?= htmlspecialchars($user_name) ?></span>!
                        <i class="bi bi-heart-fill"></i>
                    </h1>

                    <p>
                        Here's a quick look at your baby's day.
                    </p>

                </div>


                <div class="today-date">

                    <div class="today-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div>

                        <strong>Today</strong>

                        <span>
                            <?= date("l, d F Y") ?>
                        </span>

                    </div>

                </div>

            </section>



            <!-- =========================
                 SELECT BABY
            ========================== -->

            <section class="baby-selector-card">


                <!-- Baby Dropdown -->

                <div class="selected-baby dropdown">

                    <button
                        class="selected-baby-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div class="baby-icon">
                            <i class="bi bi-person-hearts"></i>
                        </div>

                        <div class="baby-info">

                            <small>
                                Selected Baby
                            </small>

                            
                                <strong>
                                    <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "No Baby" ?>
                                </strong>

                                <span>
                                    <?= $selected_baby ? htmlspecialchars($baby_age) : "No baby profile yet" ?>
                                </span>
                            

                            
                                
                    

                        </div>

                        <i class="bi bi-chevron-down baby-arrow"></i>

                    </button>


                    <!-- Baby List -->

                    <div class="dropdown-menu baby-dropdown">

                        <div class="dropdown-title">
                            Select Baby
                        </div>


                        <?php foreach ($babies as $baby): ?>

    <?php
        $is_selected = $selected_baby && $baby["id"] == $selected_baby["id"];
        
        $birth_date = new DateTime($baby["birth_date"]);
        $today = new DateTime();
        $age = $birth_date->diff($today);

        if ($age->y > 0) {
            $baby_age_item = $age->y . " Year";
            if ($age->y > 1) {
                $baby_age_item .= "s";
            }

            if ($age->m > 0) {
                $baby_age_item .= " " . $age->m . " Month";
                if ($age->m > 1) {
                    $baby_age_item .= "s";
                }
            }
        } else {
            $baby_age_item = $age->m . " Month";
            if ($age->m != 1) {
                $baby_age_item .= "s";
            }
        }
    ?>

    <button
        type="button"
        class="baby-option <?= $is_selected ? 'active' : '' ?>"
        data-baby-id="<?= $baby["id"] ?>"
    >

        <div class="mini-baby-icon">
            <i class="bi bi-person-fill"></i>
        </div>

        <div>
            <strong>
                <?= htmlspecialchars($baby["name"]) ?>
            </strong>

            <span>
                <?= htmlspecialchars($baby_age_item) ?>
            </span>
        </div>

        <?php if ($is_selected): ?>
            <i class="bi bi-check2"></i>
        <?php endif; ?>

    </button>

<?php endforeach; ?>



                        <a
                            href="baby_profile.php"
                            class="add-baby-option"
                        >

                            <i class="bi bi-plus-circle"></i>

                            Add another baby

                        </a>

                    </div>

                </div>



                <!-- Daily Tracker Button -->

                <div class="tracker-action">

                    <a
                        href="daily-tracker.php"
                        class="dashboard-primary-btn"
                    >

                        Go to Daily Tracker

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </section>



            <!-- =========================
                 TODAY'S SUMMARY
            ========================== -->

            <section class="summary-section">

                <div class="section-heading">

                    <h2>
                        Today's Summary
                    </h2>

                </div>


                <div class="row g-3">


                    <!-- Sleep -->

                    <div class="col-12 col-sm-6 col-xl-3">

                        <div class="summary-card sleep-card">

                            <div class="summary-icon">
                                <i class="bi bi-moon-stars"></i>
                            </div>

                            <div>

                                <span>
                                    Sleep
                                </span>

                                <strong>
                                    <?= $sleep_count ?>
                                </strong>
                                    
                                

                                <small>
                                    sessions
                                </small>

                            </div>

                        </div>

                    </div>


                    <!-- Feeding -->

                    <div class="col-12 col-sm-6 col-xl-3">

                        <div class="summary-card feeding-card">

                            <div class="summary-icon">
                                <i class="bi bi-cup-straw"></i>
                            </div>

                            <div>

                                <span>
                                    Feeding
                                </span>

                                <strong>
                                    <?= $feeding_count ?>
                                </strong>
                                    
                                

                                <small>
                                    times
                                </small>

                            </div>

                        </div>

                    </div>


                    <!-- Diaper -->

                    <div class="col-12 col-sm-6 col-xl-3">

                        <div class="summary-card diaper-card">

                            <div class="summary-icon">
                                <i class="bi bi-droplet"></i>
                            </div>

                            <div>

                                <span>
                                    Diaper
                                </span>

                                <strong>
                                    <?= $diaper_count ?>
                                </strong>

                                <small>
                                    changes
                                </small>

                            </div>

                        </div>

                    </div>


                    <!-- Notes -->

                    <div class="col-12 col-sm-6 col-xl-3">

                        <div class="summary-card notes-card">

                            <div class="summary-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>

                            <div>

                                <span>
                                    Notes
                                </span>

                                <strong>
                                    <?= $notes_count ?>
                                </strong>

                                <small>
                                    note
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </section>



            <!-- =========================
                 RECENT + UPCOMING
            ========================== -->

            <section class="dashboard-grid-section">


                <!-- Recent Activities -->

                <div class="dashboard-panel">

                    <div class="panel-header">
                        <h2>
                           Recent Activities
                        </h2>

                    <a href="daily-tracker.php" class="view-all">
                      View All
                    </a>
                </div>

                <?php if (count($recent_activities) > 0): ?>

    <?php foreach ($recent_activities as $activity): ?>

        <?php
            $type = $activity["record_type"];

            if ($type === "Sleep") {
                $icon = "bi-moon-stars";
                $icon_class = "sleep-icon";
                $tag_class = "sleep-tag";
            } elseif ($type === "Feeding") {
                $icon = "bi-cup-straw";
                $icon_class = "feeding-icon";
                $tag_class = "feeding-tag";
            } elseif ($type === "Diaper") {
                $icon = "bi-droplet";
                $icon_class = "diaper-icon";
                $tag_class = "diaper-tag";
            } else {
                $icon = "bi-journal-text";
                $icon_class = "notes-icon";
                $tag_class = "notes-tag";
            }

            $time = $activity["start_time"]
                ? date("g:i A", strtotime($activity["start_time"]))
                : date("g:i A", strtotime($activity["created_at"]));

            $details = $activity["details"] ?: $activity["notes"];
        ?>

        <div class="activity-item">

            <div class="activity-icon <?= $icon_class ?>">
                <i class="bi <?= $icon ?>"></i>
            </div>

            <div class="activity-details">

                <strong>
                    <?= htmlspecialchars($time) ?>
                </strong>

                <span>
                    <?= htmlspecialchars($details ?: "No details") ?>
                </span>

            </div>

            <span class="activity-tag <?= $tag_class ?>">
                <?= htmlspecialchars($type) ?>
            </span>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="activity-item">
        <div class="activity-details">
            <strong>No activities yet</strong>
            <span>Start adding records for your baby.</span>
        </div>
    </div>

<?php endif; ?>


                    


                <!-- Upcoming -->

                <div class="dashboard-panel">

                    <div class="panel-header">

                        <h2>
                            Upcoming
                        </h2>

                    </div>


                    <!-- Vaccination -->

                    <a
                        href="vaccination.php"
                        class="upcoming-item"
                    >

                        <div class="upcoming-icon vaccine-icon">
                            <i class="bi bi-bandaid"></i>
                        </div>

                        <div class="upcoming-details">

                            <strong>
                                  Vaccination
                            </strong>

                        <span>
                              <?= $upcoming_vaccine
                               ? htmlspecialchars($upcoming_vaccine["name"])
                               : "No upcoming vaccination" ?>
                        </span>

                        <small>
                              <?= $upcoming_vaccine
                              ? date("d F", strtotime($upcoming_vaccine["due_date"]))
                              : "No date" ?>
                        </small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>


                    <!-- Try New Food -->

                    <a
                        href="foods.php"
                        class="upcoming-item"
                    >

                        <div class="upcoming-icon growth-icon">
                            <i class="bi bi-egg-fried"></i>
                        </div>

                        <div class="upcoming-details">

                            <strong>
    Try New Food
</strong>

<span>
    <?= $suggested_food
        ? htmlspecialchars($suggested_food["name"])
        : "No new food available" ?>
</span>

<small>
    Today
</small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>


                    <!-- Growth -->

                    <a
                        href="baby_profile.php"
                        class="upcoming-item"
                    >

                        <div class="upcoming-icon growth-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>

                        <div class="upcoming-details">

                            <strong>
                                Growth Check
                            </strong>

                            <span>
                                Review weight & height
                            </span>

                            <small>
                                11 September
                            </small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                </div>

            </section>



            <!-- =========================
                 QUICK ACTIONS
            ========================== -->

            <section class="quick-actions-section">

                <div class="section-heading">

                    <h2>
                        Quick Actions
                    </h2>

                </div>


                <div class="row g-3">


                    <!-- Add Record -->

                    <div class="col-6 col-lg-3">

                        <a
                            href="daily-tracker.php"
                            class="quick-action-card"
                        >

                            <div class="quick-action-icon">
                                <i class="bi bi-calendar-plus"></i>
                            </div>

                            <strong>
                                Add Record
                            </strong>

                            <span>
                                Record today's activity
                            </span>

                        </a>

                    </div>


                    <!-- Add Baby -->

                    <div class="col-6 col-lg-3">

                        <a
                            href="baby_profile.php"
                            class="quick-action-card"
                        >

                            <div class="quick-action-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>

                            <strong>
                                Add Baby
                            </strong>

                            <span>
                                Add another baby profile
                            </span>

                        </a>

                    </div>



                    


                    <!-- Read Articles -->

                    <div class="col-6 col-lg-3">

                        <a
                            href="tips_articles.php"
                            class="quick-action-card"
                        >

                            <div class="quick-action-icon">
                                <i class="bi bi-book"></i>
                            </div>

                            <strong>
                                Read Articles
                            </strong>

                            <span>
                                Helpful parenting tips
                            </span>

                        </a>

                    </div>

                </div>

            </section>



            <!-- =========================
                 BOTTOM MESSAGE
            ========================== -->

            <div class="dashboard-message">

                <i class="bi bi-heart-fill"></i>

                <strong>
                    Better care today. A brighter tomorrow.
                </strong>

            </div>

        </div>

    </main>




    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    <script src="../js/script.js"></script>




    <script>
document.addEventListener("DOMContentLoaded", function () {

    const babyOptions = document.querySelectorAll(".baby-option");

    babyOptions.forEach(function (option) {

        option.addEventListener("click", function () {

            const babyId = this.dataset.babyId;

            if (!babyId) {
                return;
            }

            fetch("../php/baby/select_baby.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "baby_id=" + encodeURIComponent(babyId)
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {

                    // Reload dashboard with the newly selected baby
                    window.location.reload();

                } else {

                    alert(data.message || "Could not select baby.");

                }

            })
            .catch(error => {

                console.error("Baby selection error:", error);

                alert("Something went wrong while selecting the baby.");

            });

        });

    });

});
</script>


</body>
</html>