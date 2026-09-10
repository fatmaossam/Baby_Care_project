<?php

session_start();

require_once "../php/config/database.php";


// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];


// Get current user
$stmt = $conn->prepare("
    SELECT id, name, email
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

$result = $stmt->get_result();

$babies = [];

while ($baby = $result->fetch_assoc()) {
    $babies[] = $baby;
}

$stmt->close();


// =====================================================
// SELECT CURRENT BABY
// Use the baby selected by the user from the session
// =====================================================

$selected_baby = null;

// ==========================================================
// SELECT BABY
// ==========================================================

// First priority: baby_id from URL
if (isset($_GET["baby_id"])) {

    $requested_baby_id = (int) $_GET["baby_id"];

    foreach ($babies as $baby) {

        if ((int)$baby["id"] === $requested_baby_id) {

            $selected_baby = $baby;

            // Save selected baby in session
            $_SESSION["selected_baby_id"] = $baby["id"];

            break;
        }
    }
}


// Second priority: baby saved in session
if (!$selected_baby && !empty($_SESSION["selected_baby_id"])) {

    foreach ($babies as $baby) {

        if ((int)$baby["id"] === (int)$_SESSION["selected_baby_id"]) {

            $selected_baby = $baby;

            break;
        }
    }
}


// Default: first baby
if (!$selected_baby && !empty($babies)) {

    $selected_baby = $babies[0];

    $_SESSION["selected_baby_id"] = $selected_baby["id"];
}

/* Get vaccinations for selected baby */

$vaccinations = [];

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT
            baby_vaccinations.id,
            baby_vaccinations.vaccine_id,
            baby_vaccinations.due_date,
            baby_vaccinations.date_taken,
            baby_vaccinations.notes,
            vaccines.name AS vaccine_name,
            vaccines.description,
            vaccines.recommended_age
        FROM baby_vaccinations
        INNER JOIN vaccines
            ON baby_vaccinations.vaccine_id = vaccines.id
        WHERE baby_vaccinations.baby_id = ?
        ORDER BY baby_vaccinations.due_date ASC
    ");

    $stmt->bind_param("i", $baby_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($vaccination = $result->fetch_assoc()) {

        $vaccinations[] = $vaccination;

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
// =====================================================
// GET PENDING VACCINATIONS FOR RECORD VACCINATION
// These are vaccinations already scheduled for the
// selected baby and not taken yet.
// =====================================================
// =====================================================
// GET VACCINATIONS AVAILABLE TO RECORD
// Only for the currently selected baby
// =====================================================

// =====================================================
// GET ALL VACCINES FOR RECORD VACCINATION
// =====================================================

$vaccines = [];

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        recommended_age
    FROM vaccines
    ORDER BY id ASC
");

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $vaccines[] = $row;
}

$stmt->close();
?>
















<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vaccination | BabyCare</title>

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


<body class="vaccination-page">


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
                class="sidebar-link active"
            >
                <i class="bi bi-bandaid"></i>
                <span>Vaccination</span>
            </a>


            <a
                href="daily-tracker.php"
                class="sidebar-link"
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

            <a href="../php/auth/logout.php"
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
                    placeholder="Search vaccinations..."
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
                                <i class="bi bi-bandaid"></i>
                            </div>

                            <div>

                                <h6>
                                    Upcoming Vaccination
                                </h6>

                                <p>
                                    <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "your baby" ?> has a vaccination coming up.
                                </p>

                                <small>
                                    Today
                                </small>

                            </div>

                        </div>


                        <div class="notification-item">

                            <div class="notification-icon yellow">
                                <i class="bi bi-egg-fried"></i>
                            </div>

                            <div>

                                <h6>
                                    Try New Food
                                </h6>

                                <p>
                                    It's a good time to introduce a new food.
                                </p>

                                <small>
                                    2 hours ago
                                </small>

                            </div>

                        </div>


                        <div class="notification-item">

                            <div class="notification-icon blue">
                                <i class="bi bi-lightbulb"></i>
                            </div>

                            <div>

                                <h6>
                                    New Tip
                                </h6>

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



                <!-- User -->

                <div class="dropdown user-wrapper">

                    <button
                        class="user-account-btn"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div class="user-avatar">
                            <?= strtoupper(substr($user_name, 0, 1)) ?>
                        </div>


                        <div class="user-info">

                            <strong>
                                <?= htmlspecialchars($user_name) ?>
                            </strong>

                            <span>
                                Parent
                            </span>

                        </div>


                        <i class="bi bi-chevron-down"></i>

                    </button>


                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">

                        <li class="user-dropdown-name">

                            <strong>
                                <?= htmlspecialchars($user_name) ?>
                            </strong>

                            <span>
                                 <?= htmlspecialchars($user_email) ?>
                            </span>

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



        <!-- =================================================
             CONTENT
        ================================================== -->

        <div class="vaccination-content">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <section class="vaccination-header">


                <div>

                    <span class="vaccination-label">
                        HEALTH & IMMUNIZATION
                    </span>


                    <h1>
                        Vaccinations
                    </h1>


                    <p>
                        Keep your baby's vaccination schedule organized
                        and up to date.
                    </p>

                </div>


                <button
                    type="button"
                    class="vaccination-primary-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#recordVaccinationModal"
                >

                    <i class="bi bi-plus-lg"></i>


                    Record Vaccination

                </button>

            </section>



            <!-- =================================================
                 BABY SELECTOR
            ================================================== -->

            <section class="vaccination-baby-bar">


                <div class="vaccination-baby-info">


                    <div class="vaccination-baby-avatar">

                        <i class="bi bi-person-hearts"></i>

                    </div>


                    <div>

                       <strong>
    <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "No Baby" ?>
</strong>

<span>
    <?= htmlspecialchars($baby_age) ?> Old
</span>

                    </div>

                </div>


                <button
    type="button"
    class="vaccination-baby-selector"
    data-babies='<?= htmlspecialchars(json_encode($babies), ENT_QUOTES, "UTF-8") ?>'

                >

                    <span>
                      <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "No Baby" ?>
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </button>

            </section>



            <!-- =================================================
                 MAIN GRID
            ================================================== -->

            <section class="vaccination-layout">


                <!-- =================================================
                     LEFT SIDE
                ================================================== -->

                <div class="vaccination-main-column">


                    <!-- Status Tabs -->

                    <div class="vaccination-tabs">

                        <button
                            type="button"
                            class="vaccination-tab active"
                            data-status="all"
                        >
                            All
                        </button>


                        <button
                            type="button"
                            class="vaccination-tab"
                            data-status="upcoming"
                        >
                            Upcoming
                        </button>


                        <button
                            type="button"
                            class="vaccination-tab"
                            data-status="completed"
                        >
                            Completed
                        </button>


                        <button
                            type="button"
                            class="vaccination-tab"
                            data-status="overdue"
                        >
                            Overdue
                        </button>

                    </div>



                    <!-- Vaccination List -->
                     <div class="vaccination-main-layout">

                    <div class="vaccination-list">


                        <!-- BCG -->

                     <?php foreach ($vaccinations as $vaccination): ?>

    <?php

    $today = date("Y-m-d");

    if (!empty($vaccination["date_taken"])) {

        $status = "completed";
        $status_text = "Completed";
        $status_icon = "bi-check-circle-fill";

        $display_date = date(
            "M d, Y",
            strtotime($vaccination["date_taken"])
        );

    } elseif ($vaccination["due_date"] < $today) {

        $status = "overdue";
        $status_text = "Overdue";
        $status_icon = "bi-exclamation-triangle-fill";

        $display_date = date(
            "M d, Y",
            strtotime($vaccination["due_date"])
        );

    } else {

        $status = "upcoming";
        $status_text = "Upcoming";
        $status_icon = "bi-clock-fill";

        $display_date = date(
            "M d, Y",
            strtotime($vaccination["due_date"])
        );
    }

    ?>

    <article
        class="vaccination-card"
        data-status="<?= $status ?>"
        data-vaccine-id="<?= $vaccination["id"] ?>"
    >

        <div class="vaccination-card-icon pink-icon">
            <i class="bi bi-bandaid-fill"></i>
        </div>


        <div class="vaccination-card-info">

            <h3>
                <?= htmlspecialchars($vaccination["vaccine_name"]) ?>
            </h3>

            <span class="vaccination-recommended">
                <?= htmlspecialchars($vaccination["recommended_age"] ?? "") ?>
            </span>

            <p>
                <?= htmlspecialchars($vaccination["description"] ?? "") ?>
            </p>

        </div>


        <div class="vaccination-card-status">

            <span class="vaccine-status <?= $status ?>">

                <i class="bi <?= $status_icon ?>"></i>

                <?= $status_text ?>

            </span>

            <small>
                <?= $display_date ?>
            </small>

        </div>


        <button
            type="button"
            class="vaccination-details-btn"
            data-vaccine-id="<?= $vaccination["id"] ?>"
        >

            <i class="bi bi-chevron-right"></i>

        </button>

    </article>

<?php endforeach; ?>

</div> <!-- END vaccination-list -->


                <!-- =================================================
                     RIGHT SIDE
                ================================================== -->

                <aside class="vaccination-side-column">


                    <!-- Why Vaccinations -->

                    <div class="vaccination-info-card why-card">

                        <div class="vaccination-info-icon green-info-icon">

                            <i class="bi bi-shield-check"></i>

                        </div>


                        <div>

                            <h2>
                                Why Vaccinations?
                            </h2>

                            <p>
                                Vaccines help protect your baby
                                from serious diseases and support
                                healthy growth.
                            </p>

                        </div>

                    </div>



                    <!-- Quick Tips -->

                    <div class="vaccination-info-card tips-card">

                        <div class="vaccination-info-icon yellow-info-icon">

                            <i class="bi bi-lightbulb"></i>

                        </div>


                        <div>

                            <h2>
                                Quick Tips
                            </h2>


                            <ul>

                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Follow the recommended schedule.
                                </li>

                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Keep completed vaccinations recorded.
                                </li>

                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Consult your pediatrician when needed.
                                </li>

                            </ul>

                        </div>

                    </div>


                </aside>
                </div> <!-- END vaccination-main-layout -->


            </section>

        </div>

    </main>



    <!-- =====================================================
         VACCINATION DETAILS MODAL
    ====================================================== -->

    <div
        class="modal fade vaccination-modal"
        id="vaccinationDetailsModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <span class="vaccination-modal-label">
                            VACCINATION DETAILS
                        </span>

                        <h2
                            class="modal-title"
                            id="vaccinationDetailsTitle"
                        >
                            BCG
                        </h2>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="vaccination-modal-status">

                        <span class="vaccine-status completed">
                            <i class="bi bi-check-circle-fill"></i>
                            Completed
                        </span>

                    </div>


                    <div class="vaccination-detail-grid">

                        <div>

                            <span>
                                Recommended Age
                            </span>

                            <strong>
                                At Birth
                            </strong>

                        </div>


                        <div>

                            <span>
                                Due Date
                            </span>

                            <strong>
                                Jan 10, 2025
                            </strong>

                        </div>


                        <div>

                            <span>
                                Date Taken
                            </span>

                            <strong>
                                Jan 10, 2025
                            </strong>

                        </div>


                        <div>

                            <span>
                                Vaccine
                            </span>

                            <strong>
                                BCG
                            </strong>

                        </div>

                    </div>


                    <div class="vaccination-modal-description">

                        <span>
                            Description
                        </span>

                        <p>
                            Protects against tuberculosis.
                        </p>

                    </div>


                    <div class="vaccination-modal-notes">

                        <span>
                            Notes
                        </span>

                        <p>
                            No notes added.
                        </p>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="vaccination-secondary-btn"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>


                    <button
                          type="button"
                          class="vaccination-primary-btn vaccination-edit-record"
                    >

                        <i class="bi bi-pencil"></i>

                             Edit Record

                    </button>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         RECORD VACCINATION MODAL
    ====================================================== -->

    <div
        class="modal fade vaccination-modal"
        id="recordVaccinationModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <span class="vaccination-modal-label">
                            IMMUNIZATION RECORD
                        </span>

                        <h2 class="modal-title">
                            Record Vaccination
                        </h2>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <form
    action="../php/vaccination/add_vaccination.php"
    method="POST"
>

    <div class="modal-body">

        <!-- Baby ID -->
        <input
            type="hidden"
            name="baby_id"
            value="<?= $selected_baby ? $selected_baby['id'] : '' ?>"
        >


        <div class="vaccination-form-group">

            <label for="vaccineSelect">
                Vaccine
            </label>

            <select
    id="vaccineSelect"
    name="vaccination_id"
    required
>

    <option value="" selected disabled>
        Select vaccine
    </option>

  <?php foreach ($vaccines as $vaccine): ?>

    <option value="<?= $vaccine["id"] ?>">
        <?= htmlspecialchars($vaccine["name"]) ?>
        -
        <?= htmlspecialchars($vaccine["recommended_age"] ?? "") ?>
    </option>

<?php endforeach; ?>

</select>
        </div>


        <div class="vaccination-form-group">

            <label for="dateTaken">
                Date Taken
            </label>

            <input
                type="date"
                id="dateTaken"
                name="date_taken"
                required
            >

        </div>


        <div class="vaccination-form-group">

            <label for="vaccinationNotes">
                Notes
                <span>(Optional)</span>
            </label>

            <textarea
                id="vaccinationNotes"
                name="notes"
                rows="3"
                placeholder="Add any useful notes..."
            ></textarea>

        </div>


        <p class="vaccination-form-note">

            After saving, this record will be associated
            with the selected baby.

        </p>

    </div>


    <div class="modal-footer">

        <button
            type="button"
            class="vaccination-secondary-btn"
            data-bs-dismiss="modal"
        >
            Cancel
        </button>


        <button
            type="submit"
            class="vaccination-primary-btn"
        >

            <i class="bi bi-check-lg"></i>

            Save Record

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