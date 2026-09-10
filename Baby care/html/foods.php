<?php

session_start();

require_once "../php/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

/* Get current user */
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


/* Get babies for current user */
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


/* Select first baby for now */
$selected_baby = $babies[0] ?? null;


/* Get foods */
$stmt = $conn->prepare("
    SELECT
        id,
        name,
        food_type,
        age_from,
        age_to,
        description
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

/* Get allergies for selected baby */

$allergies = [];

if ($selected_baby) {

    $baby_id = $selected_baby["id"];

    $stmt = $conn->prepare("
        SELECT
            baby_allergies.id,
            baby_allergies.food_id,
            baby_allergies.reaction_type,
            baby_allergies.notes,
            foods.name AS food_name
        FROM baby_allergies
        INNER JOIN foods
            ON baby_allergies.food_id = foods.id
        WHERE baby_allergies.baby_id = ?
        ORDER BY baby_allergies.created_at DESC
    ");

    $stmt->bind_param("i", $baby_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($allergy = $result->fetch_assoc()) {
        $allergies[] = $allergy;
    }

    $stmt->close();
}







?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Foods | BabyCare</title>

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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="foods-page">

    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="dashboard-sidebar">

        <div class="sidebar-logo">
            <a href="dashboard.php">
               
                <img
                    src="../assets/icons/logo.png"
                    alt="BabyCare"
                >
            </a>
        </div>


        <nav class="sidebar-nav">

            <a href="dashboard.php" class="sidebar-link">
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

            <a href="foods.php" class="sidebar-link active">
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


        <div class="sidebar-bottom">

            <a href="login.html" class="sidebar-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>

        </div>

    </aside>



    <!-- =========================
         MAIN
    ========================== -->

    <main class="dashboard-main">


        <!-- =========================
             TOPBAR
        ========================== -->

        <header class="dashboard-topbar">

            <div class="dashboard-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search foods..."
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



                <!-- Account -->

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
    href="../php/auth/logout.php"
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
             CONTENT
        ========================== -->

        <div class="foods-content">


            <!-- PAGE HEADER -->

            <section class="foods-header">

                <div>

                    <span class="foods-label">
                        NUTRITION & FEEDING
                    </span>

                    <h1>
                        Baby Foods
                    </h1>

                    <p>
                        Discover healthy and age-appropriate food ideas
                        for your little one.
                    </p>

                </div>


                <a
                    href="daily-tracker.php"
                    class="foods-primary-btn"
                >

                    <i class="bi bi-journal-plus"></i>

                    Record a Meal

                </a>

            </section>



            <!-- =========================
                 BABY INFO
            ========================== -->

            <section class="foods-baby-bar">

                <div class="foods-baby-info">

                    <div class="foods-baby-avatar">
                        <i class="bi bi-person-hearts"></i>
                    </div>

                    <div>

                        <small>
                            Foods for
                        </small>

                        <strong>
                            <?= $selected_baby ? htmlspecialchars($selected_baby["name"]) : "No Baby" ?>
                        </strong>

                        <span>
                            <?php
    if ($selected_baby) {

        $birthDate = new DateTime($selected_baby["birth_date"]);
        $today = new DateTime();

        $age = $birthDate->diff($today);

        if ($age->y > 0) {

            echo $age->y . " Year";

            if ($age->y > 1) {
                echo "s";
            }

            if ($age->m > 0) {
                echo " " . $age->m . " Month";

                if ($age->m > 1) {
                    echo "s";
                }
            }

        } else {

            echo $age->m . " Month";

            if ($age->m != 1) {
                echo "s";
            }
        }

        echo " Old";

    } else {

        echo "No Baby";

    }
    ?>
                        </span>

                    </div>

                </div>


                <a
                    href="baby_profile.php"
                    class="foods-baby-link"
                >
                    View Baby Profile
                    <i class="bi bi-arrow-right"></i>
                </a>

            </section>



            <!-- =========================
                 FILTERS
            ========================== -->

            <section class="foods-filters">

                <div class="food-tabs">

                    <button type="button" class="food-tab active" data-category="All">
                        All
                    </button>

                    <button type="button" class="food-tab" data-category="Fruits">
                        Fruits
                    </button>

                    <button type="button" class="food-tab" data-category="Vegetables">
                        Vegetables
                    </button>

                    <button type="button" class="food-tab" data-category="Grains">
                        Grains
                    </button>

                    <button type="button" class="food-tab" data-category="Proteins">
                        Proteins
                    </button>

                    <button type="button" class="food-tab" data-category="Dairy">
                        Dairy
                    </button>

                </div>


                <select class="age-filter" aria-label="Filter foods by baby age">

                    <option>
                        All Ages
                    </option>

                    <option>
                        6+ Months
                    </option>

                    <option>
                        8+ Months
                    </option>

                    <option>
                        12+ Months
                    </option>

                </select>

            </section>



            <!-- =========================
                 FOOD CARDS
            ========================== -->

            <section class="foods-grid">

    <?php foreach ($foods as $food): ?>

        <?php
            $food_name = $food["name"];
            $food_type = $food["food_type"];
            $age_from = $food["age_from"];

            $food_slug = strtolower(
                str_replace(" ", "-", $food_name)
            );

            $age_text = $age_from . "+ Months";
        ?>

        <div
            class="food-card"
            data-food="<?= htmlspecialchars($food_slug) ?>"
            data-category="<?= htmlspecialchars($food_type) ?>"
            data-age-from="<?= htmlspecialchars($age_from) ?>"
        >

            <div class="food-image">

                <img
                    src="../assets/images/<?= htmlspecialchars($food_slug) ?>.png"
                    alt="<?= htmlspecialchars($food_name) ?>"
                >

                <span class="food-category">
                    <?= htmlspecialchars($food_type) ?>
                </span>

            </div>


            <div class="food-card-body">

                <h3>
                    <?= htmlspecialchars($food_name) ?>
                </h3>

                <span class="food-age">
                    <?= htmlspecialchars($age_text) ?>
                </span>

                <p>
                    <?= htmlspecialchars($food["description"]) ?>
                </p>

                <button
                    type="button"
                    class="food-details-btn"
                    data-food="<?= htmlspecialchars($food_slug) ?>"
                >
                    View Details
                    <i class="bi bi-arrow-right"></i>
                </button>

            </div>

        </div>

    <?php endforeach; ?>

</section>



            <!-- =========================
                 LOWER SECTION
            ========================== -->

            <section class="foods-lower-section">


                <!-- Allergies -->

                <div class="food-info-panel allergy-panel">

                    <div class="food-panel-heading">

                        <div class="panel-heading-icon allergy-heading-icon">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>

                        <div>

                            <h2>
                                Food Allergies
                            </h2>

                            <p>
                                Keep track of foods that may cause
                                a reaction.
                            </p>

                        </div>

                    </div>


                    <div class="allergy-empty">

    <?php if (count($allergies) === 0): ?>

        <i class="bi bi-check-circle"></i>

        <div>
            <strong>No allergies recorded</strong>

            <span>
                Any food reactions you add will appear here.
            </span>
        </div>

    <?php else: ?>

        <?php foreach ($allergies as $allergy): ?>

            <i class="bi bi-exclamation-circle"></i>

            <div>

                <strong>
                    <?= htmlspecialchars($allergy["food_name"]) ?>
                    —
                    <?= htmlspecialchars($allergy["reaction_type"]) ?>
                </strong>

                <span>
                    <?= !empty($allergy["notes"])
                        ? htmlspecialchars($allergy["notes"])
                        : "Food reaction recorded."
                    ?>
                </span>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>


                    <button
                        type="button"
                        class="panel-text-link allergy-add-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#addAllergyModal"
                    >
                        Add Allergy
                        <i class="bi bi-plus-lg"></i>
                    </button>

                </div>



                <!-- Quick Tips -->

                <div class="food-info-panel tips-panel">

                    <div class="food-panel-heading">

                        <div class="panel-heading-icon tips-heading-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>

                        <div>

                            <h2>
                                Quick Tips
                            </h2>

                            <p>
                                Simple feeding reminders.
                            </p>

                        </div>

                    </div>


                    <ul class="food-tips-list">

                        <li>
                            <i class="bi bi-check2"></i>
                            Introduce one new food at a time.
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Watch for signs of an allergic reaction.
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Choose fresh and natural ingredients.
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Avoid added salt and sugar.
                        </li>

                    </ul>

                </div>

            </section>

        </div>


        <!-- =========================
             FOOD DETAILS MODAL
        ========================== -->

        <div class="modal fade foods-modal" id="foodDetailsModal" tabindex="-1" aria-labelledby="foodDetailsTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <span class="modal-eyebrow">FOOD DETAILS</span>
                            <h2 class="modal-title" id="foodDetailsTitle">Banana</h2>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="food-details-preview">
                            <div class="food-details-image">
                                <img id="foodDetailsImage" src="../assets/images/banana.png" alt="Banana">
                            </div>

                            <div class="food-details-meta">
                                <span id="foodDetailsCategory">Fruits</span>
                                <strong id="foodDetailsAge">6+ Months</strong>
                            </div>
                        </div>

                        <p class="food-details-description" id="foodDetailsDescription">
                            Soft and naturally sweet, rich in potassium and easy for babies to eat.
                        </p>

                        <div class="food-details-info-grid">
                            <div>
                                <span>Serving idea</span>
                                <strong id="foodDetailsServing">Mashed or softly sliced.</strong>
                            </div>

                            <div>
                                <span>Important note</span>
                                <strong id="foodDetailsNote">Serve in a baby-safe texture and size.</strong>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="foods-secondary-btn" data-bs-dismiss="modal">
                            Close
                        </button>

                        <a id="recordMealFromDetails" href="daily-tracker.php?record=meal&food=banana" class="foods-primary-btn">
    <i class="bi bi-journal-plus"></i>
    Record This Meal
</a>
                    </div>

                </div>
            </div>
        </div>


        <!-- =========================
             ADD ALLERGY MODAL
        ========================== -->

        <div class="modal fade foods-modal" id="addAllergyModal" tabindex="-1" aria-labelledby="addAllergyTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <span class="modal-eyebrow">FOOD ALLERGIES</span>
                            <h2 class="modal-title" id="addAllergyTitle">Add Food Allergy</h2>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="allergyForm" action="../php/food/add_allergy.php" method="POST">
                        <input
                            type="hidden"
                            name="baby_id"
                            value="<?= $selected_baby ? $selected_baby['id'] : '' ?>"
                        >
                        <div class="modal-body">

                            <div class="foods-form-group">
                                <label for="allergyFood">Food</label>
                                <select id="allergyFood" name="food_id" required>
                                    <option value="1">Banana</option>
                                    <option value="2">Apple</option>
                                    <option value="3">Avocado</option>
                                    <option value="4">Carrot</option>
                                    <option value="5">Sweet Potato</option>
                                    <option value="6">Broccoli</option>
                                    <option value="7">Oats</option>
                                    <option value="8">Rice Cereal</option>
                                    <option value="9">Chicken</option>
                                    <option value="10">Egg</option>
                                    <option value="11">Lentils</option>
                                    <option value="12">Yogurt</option>
                                </select>
                            </div>

                            <div class="foods-form-group">
                                <label for="reactionType">Reaction</label>
                                <select id="reactionType" name="reaction_type" required>
                                    <option value="" selected disabled>Select reaction</option>
                                    <option>Skin rash</option>
                                    <option>Vomiting</option>
                                    <option>Diarrhea</option>
                                    <option>Swelling</option>
                                    <option>Breathing difficulty</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="foods-form-group">
                                <label for="allergyNotes">Notes <span>(Optional)</span></label>
                                <textarea id="allergyNotes" name="notes" rows="3" placeholder="Add any useful notes..."></textarea>
                            </div>

                            <p class="foods-form-note">
                                This form is currently a frontend demo. Later, the saved data will be sent to <strong>BABY_ALLERGIES</strong>.
                            </p>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="foods-secondary-btn" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="foods-primary-btn">
                                <i class="bi bi-check-lg"></i>
                                Save Allergy
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </main>



    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <!-- Main JS -->
    <script src="../js/script.js"></script>

</body>
</html>