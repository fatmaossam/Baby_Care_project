<?php

session_start();

require_once "../php/config/database.php";

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT name
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

$user_name = $user["name"];

// Get user's babies
$stmt = $conn->prepare("
    SELECT id, name, birth_date
    FROM babies
    WHERE user_id = ?
    ORDER BY id ASC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$babies = [];

while ($baby = $result->fetch_assoc()) {
    $babies[] = $baby;
}

$stmt->close();



// Select first baby for now
$selected_baby_id = intval($_GET["baby_id"] ?? 0);

$selected_date = $_GET["date"] ?? date("Y-m-d");

$selectedDateObject = new DateTime($selected_date);

if ($selected_date === date("Y-m-d")) {
    $daily_date_text = "Today, " . $selectedDateObject->format("M j, Y");
} else {
    $daily_date_text = $selectedDateObject->format("l, M j, Y");
}

$selected_baby = null;

foreach ($babies as $baby) {
    if ($baby["id"] == $selected_baby_id) {
        $selected_baby = $baby;
        break;
    }
}

if (!$selected_baby && count($babies) > 0) {
    $selected_baby = $babies[0];
}

$baby_age = "";

if ($selected_baby) {

    $birthDate = new DateTime($selected_baby["birth_date"]);
    $today = new DateTime();

    $age = $birthDate->diff($today);

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

    $baby_age .= " Old";
}

// Get selected baby's records for today
$records = [];

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT
            id,
            record_type,
            details,
            start_time,
            end_time,
            notes,
            created_at
        FROM daily_records
        WHERE baby_id = ?
        AND DATE(created_at) = ?
        ORDER BY created_at DESC
    ");

    $stmt->bind_param("is", $baby_id, $selected_date);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($record = $result->fetch_assoc()) {
        $records[] = $record;
    }

    $stmt->close();
}

// =====================================
// Get foods
// =====================================

$stmt = $conn->prepare("
    SELECT
        id,
        name
    FROM foods
    ORDER BY id ASC
");

$stmt->execute();

$result = $stmt->get_result();

$foods = [];

while ($food = $result->fetch_assoc()) {
    $foods[] = $food;
}

$stmt->close();



// Records count
$records_count = count($records);

?>







<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daily Tracker | BabyCare</title>

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

    <!-- Main CSS -->
    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body class="daily-tracker-page">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="dashboard-sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">

            <a href="dashboard.php">

                <img
                    src="../assets/icons/logo.png"
                    alt="BabyCare"
                >

            </a>

        </div>


        <!-- Navigation -->
        <nav class="sidebar-nav">

            <a
                href="dashboard.php"
                class="sidebar-link"
            >
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>


            <a
                href="profile.php"
                class="sidebar-link"
            >
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>


            <a
                href="baby_profile.php"
                class="sidebar-link"
            >
                <i class="bi bi-people"></i>
                <span>Baby Profile</span>
            </a>


            <a
                href="foods.php"
                class="sidebar-link"
            >
                <i class="bi bi-egg-fried"></i>
                <span>Foods</span>
            </a>


            <a
                href="vaccination.php"
                class="sidebar-link"
            >
                <i class="bi bi-bandaid"></i>
                <span>Vaccination</span>
            </a>


            <a
                href="daily-tracker.php"
                class="sidebar-link active"
            >
                <i class="bi bi-calendar-check"></i>
                <span>Daily Tracker</span>
            </a>


            <a
                href="tips_articles.php"
                class="sidebar-link"
            >
                <i class="bi bi-journal-text"></i>
                <span>Tips & Articles</span>
            </a>


            <a
                href="settings.php"
                class="sidebar-link"
            >
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>

        </nav>


        <!-- Logout -->
        <div class="sidebar-bottom">

            <a
    href="../php/auth/logout.php"
    class="sidebar-link logout-link"
>
    <i class="bi bi-box-arrow-right"></i>
    <span>Logout</span>
</a>

        </div>

    </aside>



    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             TOPBAR
        ================================================== -->

        <header class="dashboard-topbar">


            <!-- Search -->
            <div class="dashboard-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="dailyTrackerSearch"
                    placeholder="Search records..."
                >

            </div>



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
                            3
                        </span>

                    </button>


                    <div class="dropdown-menu dropdown-menu-end notification-menu">

                        <div class="notification-header">

                            <strong>
                                Notifications
                            </strong>

                            <button type="button">
                                Mark all as read
                            </button>

                        </div>


                        <div class="notification-item">

                            <div class="notification-icon pink">
                                <i class="bi bi-bell"></i>
                            </div>

                            <div>
                                <strong>Vaccination Reminder</strong>
                                <p>Upcoming vaccination for <?= $selected_baby ? htmlspecialchars($selected_baby['name']) : 'your baby' ?></p>
                            </div>

                        </div>


                        <div class="notification-item">

                            <div class="notification-icon yellow">
                                <i class="bi bi-lightbulb"></i>
                            </div>

                            <div>
                                <strong>New Tip</strong>
                                <p>Check today's baby care tip.</p>
                            </div>

                        </div>


                        <div class="notification-item">

                            <div class="notification-icon blue">
                                <i class="bi bi-calendar-check"></i>
                            </div>

                            <div>
                                <strong>Daily Tracker</strong>
                                <p>Don't forget to record today's activities.</p>
                            </div>

                        </div>

                    </div>

                </div>



                <!-- User -->
                <div class="dropdown user-wrapper">

                    <button
                        class="user-account-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <span class="user-avatar">
                            <?= strtoupper(substr($user_name, 0, 1)) ?>
                        </span>

                        <span class="user-info">

                            <strong>
                                <?= htmlspecialchars($user_name) ?>
                            </strong>

                            <small>
                                Parent
                            </small>

                        </span>

                        <i class="bi bi-chevron-down"></i>

                    </button>


                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">

                        <li>
                            <a
                                class="dropdown-item"
                                href="profile.php"
                            >
                                <i class="bi bi-person"></i>
                                Profile
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
                                class="dropdown-item"
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



        <!-- =================================================
             PAGE CONTENT
        ================================================== -->

        <div class="daily-tracker-content">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <section class="daily-tracker-header">

                <div>

                    <span class="daily-tracker-label">
                        BABY CARE
                    </span>

                    <h1>
                        Daily Tracker
                    </h1>

                    <p>
                        Keep track of your baby's daily activities in one place.
                    </p>

                </div>

            </section>



            <!-- =================================================
                 BABY + DATE BAR
            ================================================== -->

            <section class="daily-control-bar">


                <!-- Baby -->
                <div class="daily-baby-info">

                    <div class="daily-baby-avatar">

                        <i class="bi bi-person-hearts"></i>

                    </div>


                    <div>

                        <small>
                            Selected Baby
                        </small>

                        <strong id="selectedBabyName">
    <?= $selected_baby
        ? htmlspecialchars($selected_baby["name"])
        : "No Baby"
    ?>
</strong>

<span id="selectedBabyAge">
    <?= $selected_baby
        ? htmlspecialchars($baby_age)
        : "No baby profile yet"
    ?>
</span>

                    </div>

                </div>



                <div class="daily-control-actions">


                    <!-- Baby Selector -->
                    <div class="daily-baby-selector-wrapper">

                        <button
    type="button"
    class="daily-baby-selector"
    id="dailyBabySelector"
>
    <span>
        <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "No Baby" ?>
    </span>

    <i class="bi bi-chevron-down"></i>
</button>


                        <div
                            class="daily-baby-dropdown"
                            id="dailyBabyDropdown"
                        >

                            <?php foreach ($babies as $baby): ?>

    <?php
        $birthDate = new DateTime($baby["birth_date"]);
        $today = new DateTime();
        $age = $birthDate->diff($today);

        if ($age->y > 0) {
            $babyAge = $age->y . " Year";
            if ($age->y > 1) {
                $babyAge .= "s";
            }

            if ($age->m > 0) {
                $babyAge .= " " . $age->m . " Month";
                if ($age->m > 1) {
                    $babyAge .= "s";
                }
            }
        } else {
            $babyAge = $age->m . " Month";
            if ($age->m != 1) {
                $babyAge .= "s";
            }
        }
    ?>

    <button
        type="button"
        class="daily-baby-option <?= ($selected_baby && $selected_baby['id'] == $baby['id']) ? 'active' : '' ?>"
        data-baby="<?= htmlspecialchars($baby['name']) ?>"
        data-baby-id="<?= $baby['id'] ?>"
        data-age="<?= htmlspecialchars($babyAge) ?>"
    >

        <span><?= htmlspecialchars($baby['name']) ?></span>

        <i class="bi bi-check"></i>

    </button>

<?php endforeach; ?>

                                

                                <i class="bi bi-check"></i>

                            </button>

                        </div>

                    </div>



                    <!-- Date -->
                    <div class="daily-date-wrapper">

                        <button
                            type="button"
                            class="daily-date-btn"
                            id="dailyDateBtn"
                        >

                            <i class="bi bi-calendar3"></i>

                           <span id="dailyDateText">
                               <?= htmlspecialchars($daily_date_text) ?>
                           </span>

                            <i class="bi bi-chevron-down"></i>

                        </button>

                        <input
                            type="date"
                            id="dailyDateInput"
                            class="daily-hidden-date"
                            value="<?= htmlspecialchars($selected_date) ?>"
                        >

                    </div>

                </div>

            </section>



            <!-- =================================================
                 MAIN GRID
            ================================================== -->

            <section class="daily-tracker-layout">


                <!-- =================================================
                     LEFT SIDE
                ================================================== -->

                <div class="daily-main-column">


                    <!-- Today's Records -->
                    <section class="daily-records-panel">


                        <div class="daily-panel-header">

                            <div>

                                <h2>
                                    Today's Records
                                </h2>

                                <span id="recordsCount">
                                    7 Records
                                </span>

                            </div>

                        </div>



                        <!-- Records List -->
                        <div
                            class="daily-records-list"
                            id="dailyRecordsList"
                        >


                            <?php if (count($records) > 0): ?>

    <?php foreach ($records as $record): ?>

        <?php
            $type = $record["record_type"];

            if ($type === "Sleep") {
                $icon = "bi-moon-stars";
                $icon_class = "sleep-record-icon";
            } elseif ($type === "Feeding") {
                $icon = "bi-cup-straw";
                $icon_class = "feeding-record-icon";
            } elseif ($type === "Diaper") {
                $icon = "bi-droplet";
                $icon_class = "diaper-record-icon";
            } else {
                $icon = "bi-journal-text";
                $icon_class = "note-record-icon";
            }

            if ($record["start_time"]) {
                $display_time = date("h:i A", strtotime($record["start_time"]));
            } else {
                $display_time = date("h:i A", strtotime($record["created_at"]));
            }

            $details = $record["details"] ?: $record["notes"];
        ?>

        <article class="daily-record-item"
                 data-id="<?= $record['id'] ?>"
                 data-type="<?= strtolower($type) ?>"
                 data-start-time="<?= htmlspecialchars($record['start_time'] ?? '') ?>"
                 data-end-time="<?= htmlspecialchars($record['end_time'] ?? '') ?>"
                 data-notes="<?= htmlspecialchars($record['notes'] ?? '') ?>"
                 data-search="<?= htmlspecialchars(strtolower($type . ' ' . $details)) ?>">
                 

            <div class="daily-record-icon <?= $icon_class ?>">
                <i class="bi <?= $icon ?>"></i>
            </div>

            <div class="daily-record-time">
                <?= htmlspecialchars($display_time) ?>
            </div>

            <div class="daily-record-details">

                <strong>
                    <?= htmlspecialchars($type) ?>
                </strong>

                <p>
                    <?= htmlspecialchars($details ?: "No details") ?>
                </p>

            </div>

            <div class="daily-record-menu">

                <button type="button" class="daily-more-btn">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>

                <div class="daily-record-dropdown">

                    <button type="button" class="daily-edit-record">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>

                    <button type="button" class="daily-delete-record">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>

                </div>

            </div>

        </article>

    <?php endforeach; ?>

<?php endif; ?>

                            <!-- Empty State -->
                            <div
                                class="daily-empty-state"
                                id="dailyEmptyState"
                            >

                                <div class="daily-empty-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>

                                <h3>
                                    No records found
                                </h3>

                                <p>
                                    There are no records matching your search.
                                </p>

                            </div>

                        </div>

                    </section>

                </div>



                <!-- =================================================
                     RIGHT SIDE
                ================================================== -->

                <aside class="daily-side-column">


                    <!-- Add Record Card -->
                    <div class="daily-info-card add-record-card">

                        <div class="daily-info-icon pink-daily-icon">

                            <i class="bi bi-plus-lg"></i>

                        </div>


                        <h2>
                            Add New Record
                        </h2>

                        <p>
                            Track your baby's activities easily and keep the day organized.
                        </p>


                        <button
                            type="button"
                            class="daily-side-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#recordModal"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Add Record

                        </button>

                    </div>



                    <!-- Quick Tip -->
                    <div class="daily-info-card quick-tip-card">

                        <div class="daily-info-icon blue-daily-icon">

                            <i class="bi bi-lightbulb"></i>

                        </div>


                        <h2>
                            Quick Tip
                        </h2>


                        <p>
                            Keep a consistent routine to help your baby feel safe and secure.
                        </p>


                        <div class="tip-decoration">

                            <i class="bi bi-flower1"></i>

                        </div>

                    </div>

                </aside>

            </section>

        </div>

    </main>



    <!-- =====================================================
         ADD / EDIT RECORD MODAL
    ====================================================== -->

    <div
        class="modal fade daily-modal"
        id="recordModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <span class="daily-modal-label">
                            DAILY ACTIVITY
                        </span>

                        <h2
                            class="modal-title"
                            id="recordModalTitle"
                        >
                            Add New Record
                        </h2>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>



                <form id="recordForm" action="../php/tracker/add_record.php" method="POST">
                    <input type="hidden" name="baby_id" value="<?= $selected_baby ? $selected_baby['id'] : '' ?>">

                    <input type="hidden" name="record_date" value="<?= date('Y-m-d') ?>">

                    <div class="modal-body">


                        <!-- Record Type -->
                        <div class="daily-form-group">

                            <label for="recordType">
                                Record Type
                            </label>

                            <select
                               class="form-select"
                               id="recordType"
                               name="record_type"
                               required
                            >

                                <option value="">
                                    Select record type
                                </option>

                                <option value="sleep">
                                    Sleep
                                </option>

                                <option value="feeding">
                                    Feeding
                                </option>

                                <option value="diaper">
                                    Diaper
                                </option>

                                <option value="note">
                                    Note
                                </option>

                            </select>

                        </div>



                        <!-- Time -->
                        <div
                            class="daily-form-group"
                            id="singleTimeGroup"
                        >

                            <label for="recordTime">
                                Time
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="recordTime"
                                name="time"
                            >

                        </div>



                        <!-- Sleep Fields -->
                        <div
                            class="daily-dynamic-fields"
                            id="sleepFields"
                        >

                            <div class="daily-form-row">

                                <div class="daily-form-group">

                                    <label for="sleepStart">
                                        Start Time
                                    </label>

                                    <input
                                        type="time"
                                        class="form-control"
                                        id="sleepStart"
                                        name="sleep_start"
                                    >

                                </div>


                                <div class="daily-form-group">

                                    <label for="sleepEnd">
                                        End Time
                                    </label>

                                    <input
                                        type="time"
                                        class="form-control"
                                        id="sleepEnd"
                                        name="sleep_end"
                                    >

                                </div>

                            </div>

                        </div>



                       <!-- Feeding Fields -->
<div class="daily-dynamic-fields" id="feedingFields">

    <div class="daily-form-row">

        <!-- Feeding Type -->
        <div class="daily-form-group">
            <label for="feedingType">
                Feeding Type
            </label>

            <select
                class="form-select"
                id="feedingType"
                name="feeding_type"
            >
                <option value="">Select type</option>
                <option value="Breast milk">Breast milk</option>
                <option value="Formula">Formula</option>
                <option value="Solid food">Solid food</option>
            </select>
        </div>


        <!-- Quantity -->
        <div class="daily-form-group">
            <label for="feedingQuantity">
                Quantity
            </label>

            <input
                type="text"
                class="form-control"
                id="feedingQuantity"
                name="quantity"
                placeholder="e.g. 120 ml"
            >
        </div>

    </div>


    <!-- Food -->
    <div
        class="daily-form-group"
        id="foodSelectionGroup"
        style="display: none;"
    >

        <label for="foodId">
            Food
        </label>

        <select
            class="form-select"
            id="foodId"
            name="food_id"
        >

            <option value="">
                Select food
            </option>

            <?php foreach ($foods as $food): ?>

                <option value="<?= $food['id'] ?>">
                    <?= htmlspecialchars($food['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <!-- Reaction -->
    <div class="daily-form-group">

        <label for="feedingReaction">
            Reaction
        </label>

        <input
            type="text"
            class="form-control"
            id="feedingReaction"
            name="reaction"
            placeholder="Optional"
        >

    </div>

</div>



                        <!-- Diaper Fields -->
                        <div
                            class="daily-dynamic-fields"
                            id="diaperFields"
                        >

                            <div class="daily-form-group">

                                <label>
                                    Diaper Type
                                </label>


                                <div class="daily-radio-group">

                                    <label class="daily-radio-option">

                                        <input
                                            type="radio"
                                            name="diaper_type"
                                            value="Wet"
                                        >

                                        <span>
                                            Wet
                                        </span>

                                    </label>


                                    <label class="daily-radio-option">

                                        <input
                                            type="radio"
                                            name="diaper_type"
                                            value="Dirty"
                                        >

                                        <span>
                                            Dirty
                                        </span>

                                    </label>


                                    <label class="daily-radio-option">

                                        <input
                                            type="radio"
                                            name="diaper_type"
                                            value="Wet & Dirty"
                                        >

                                        <span>
                                            Wet & Dirty
                                        </span>

                                    </label>

                                </div>

                            </div>

                        </div>



                        <!-- Note Fields -->
                        <div
                            class="daily-dynamic-fields"
                            id="noteFields"
                        >

                            <div class="daily-form-group">

                                <label for="noteText">
                                    Note
                                </label>

                                <textarea
                                    class="form-control"
                                    id="noteText"
                                    name="note_text"
                                    rows="4"
                                    placeholder="Write a note about your baby's day..."
                                ></textarea>

                            </div>

                        </div>



                        <!-- General Notes -->
                        <div class="daily-form-group">

                            <label for="recordNotes">
                                Additional Notes
                                <span>(Optional)</span>
                            </label>

                            <textarea
                                class="form-control"
                                id="recordNotes"
                                name="additional_notes"
                                rows="3"
                                placeholder="Anything else you'd like to remember..."
                            ></textarea>

                        </div>

                    </div>



                    <div class="modal-footer">

                        <button
                            type="button"
                            class="daily-secondary-btn"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="daily-primary-btn"
                        >

                            <i class="bi bi-check-lg"></i>

                            <span id="saveRecordText">
                                Save Record
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    <!-- Main JS -->
    <script src="../js/script.js"></script>


</body>

</html>