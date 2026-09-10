<?php

session_start();

require_once "../php/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

/* =========================
   GET CURRENT USER
========================= */

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        birth_date,
        gender,
        address,
        language,
        timezone,
        about,
        profile_image,
        two_factor_enabled,
        email_notifications,
        push_notifications,
        tips_reminders,
        created_at
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();

    session_unset();
    session_destroy();

    header("Location: login.html");
    exit;
}

$user = $result->fetch_assoc();

$stmt->close();


/* =========================
   HELPERS
========================= */

function e($value)
{
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

$firstLetter = mb_substr(
    $user["name"],
    0,
    1,
    "UTF-8"
);


/* =========================
   DEFAULT PROFILE IMAGE
========================= */

$profileImage = !empty($user["profile_image"])
    ? "../" . ltrim($user["profile_image"], "/")
    : "../assets/images/momy.jpg";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Baby Care - My Profile</title>

    <link
        rel="icon"
        type="image/png"
        href="../assets/icons/logo.png"
    >

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

    <!-- Poppins -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Main CSS -->
    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body class="dashboard-page profile-page-body">


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="dashboard-sidebar">

    <!-- Logo -->

    <a href="dashboard.php" class="sidebar-logo">

    <img
        src="../assets/icons/logo.png"
        alt="Baby Care"
        class="sidebar-logo-image"
    >

</a>


    <!-- Navigation -->

    <nav class="sidebar-nav">

        <a
            href="dashboard.php"
            class="sidebar-link"
        >
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>


        <a
            href="profile.php"
            class="sidebar-link active"
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


        <a href="foods.php" class="sidebar-link">
                <i class="bi bi-egg-fried"></i>
                <span>Foods</span>
            </a>


        <a
            href="vaccination.php"
            class="sidebar-link"
        >
            <i class="bi bi-bandaid"></i>
            <span>Vaccinations</span>
        </a>


        <a
            href="daily-tracker.php"
            class="sidebar-link"
        >
            <i class="bi bi-calendar3"></i>
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
===================================================== -->

<main class="dashboard-main">


    <!-- =================================================
         TOP BAR
    ================================================== -->

    <header class="dashboard-topbar">


        <!-- Search -->

        <!-- Search -->

<div class="dashboard-search">

    <i class="bi bi-search"></i>

 <input
    type="text"
    id="globalSearch"
    name="search_query"
    placeholder="Search anything..."
    autocomplete="off"
    readonly
>

</div>


        <!-- Right Actions -->

        <div class="dashboard-top-actions">


            <!-- Notifications -->

            <div class="dropdown">

                <button
                    class="notification-btn"
                    id="notificationBtn"
                    type="button"
                >

                    <i class="bi bi-bell"></i>

                    <span class="notification-count">
                        3
                    </span>

                </button>


                <div
                    class="dropdown-menu dropdown-menu-end notification-menu"
                    id="notificationMenu"
                >

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
                                Your baby has a vaccination coming up.
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

            <div class="dropdown">

                <button
                    class="user-account-btn"
                    id="userBtn"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >

                    <div class="user-avatar">

                        <?= e($firstLetter) ?>

                    </div>


                    <div class="user-info">

                        <strong>
                            <?= e($user["name"]) ?>
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
                            <?= e($user["name"]) ?>
                        </strong>

                        <span>
                            <?= e($user["email"]) ?>
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



    <!-- =================================================
         PROFILE CONTENT
    ================================================== -->

    <div class="dashboard-content profile-content">


        <!-- PAGE HEADER -->

        <div class="profile-title">

            <span class="section-label">
                ACCOUNT
            </span>

            <h1>
                My Profile
            </h1>

            <p>
                Manage your personal information and account settings.
            </p>

        </div>



        <!-- =================================================
             PROFILE GRID
        ================================================== -->

        <div class="profile-layout">


            <!-- =================================================
                 LEFT
            ================================================== -->

            <section class="profile-left">


                <!-- PROFILE CARD -->

                <div class="profile-card dashboard-panel">

                    <div class="profile-main-info">


                        <div class="profile-image-wrapper">

                            <img
                                src="<?= e($profileImage) ?>"
                                id="momImage"
                                class="profile-image"
                                alt="<?= e($user["name"]) ?>"
                            >


                            <button
                                class="camera-btn"
                                type="button"
                                onclick="openPhotoPicker()"
                            >
                                <i class="bi bi-camera-fill"></i>
                            </button>


                            <input
                                type="file"
                                id="momPhoto"
                                accept="image/png,image/jpeg,image/webp"
                                hidden
                            >

                        </div>
                        <div id="photoUploadMessage"></div>



                        <div class="profile-user-info">

                            <div class="profile-name-row">

                                <h2 id="profileName">
                                    <?= e($user["name"]) ?>
                                </h2>

                                <span class="parent-badge">
                                    Parent
                                </span>

                            </div>


                            <p>

                                <i class="bi bi-envelope"></i>

                                <span id="profileEmail">
                                    <?= e($user["email"]) ?>
                                </span>

                            </p>


                            <p>

                                <i class="bi bi-telephone"></i>

                                <span id="profilePhone">
                                    <?= e($user["phone"]) ?>
                                </span>

                            </p>


                            <p>

                                <i class="bi bi-geo-alt"></i>

                                <span id="profileAddress">
                                    <?= e($user["address"]) ?>
                                </span>

                            </p>


                            <p>

                                <i class="bi bi-calendar"></i>

                                Joined on
                                <?= e(date(
                                    "d F Y",
                                    strtotime($user["created_at"] ?? "now")
                                )) ?>

                            </p>

                        </div>

                    </div>

                </div>



                <!-- PERSONAL INFORMATION -->

                <div class="personal-card dashboard-panel">

                    <div class="personal-header">

                        <h3>
                            Personal Information
                        </h3>


                        <button
                            class="edit-btn"
                            type="button"
                            onclick="openEditProfile()"
                        >
                            <i class="bi bi-pencil"></i>
                            Edit
                        </button>

                    </div>


                    <!-- NAME -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-person"></i>
                            </span>

                            Full Name

                        </div>

                        <div
                            class="personal-value"
                            id="valueName"
                        >
                            <?= e($user["name"]) ?>
                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-envelope"></i>
                            </span>

                            Email Address

                        </div>

                        <div
                            class="personal-value"
                            id="valueEmail"
                        >
                            <?= e($user["email"]) ?>
                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-telephone"></i>
                            </span>

                            Phone Number

                        </div>

                        <div
                            class="personal-value"
                            id="valuePhone"
                        >
                            <?= e($user["phone"]) ?>
                        </div>

                    </div>


                    <!-- BIRTH DATE -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-calendar"></i>
                            </span>

                            Date of Birth

                        </div>

                        <div
                            class="personal-value"
                            id="valueBirth"
                        >
                            <?= e($user["birth_date"]) ?>
                        </div>

                    </div>


                    <!-- GENDER -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-gender-female"></i>
                            </span>

                            Gender

                        </div>

                        <div
                            class="personal-value"
                            id="valueGender"
                        >
                            <?= e($user["gender"]) ?>
                        </div>

                    </div>


                    <!-- ADDRESS -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            Address

                        </div>

                        <div
                            class="personal-value"
                            id="valueAddress"
                        >
                            <?= e($user["address"]) ?>
                        </div>

                    </div>


                    <!-- LANGUAGE -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-globe"></i>
                            </span>

                            Language

                        </div>

                        <div
                            class="personal-value"
                            id="valueLanguage"
                        >
                            <?= e($user["language"]) ?>
                        </div>

                    </div>


                    <!-- TIMEZONE -->

                    <div class="personal-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-clock"></i>
                            </span>

                            Time Zone

                        </div>

                        <div
                            class="personal-value"
                            id="valueTimezone"
                        >
                            <?= e($user["timezone"]) ?>
                        </div>

                    </div>


                    <!-- ABOUT -->

                    <div class="personal-row about-row">

                        <div class="personal-label">

                            <span class="info-icon">
                                <i class="bi bi-chat-heart"></i>
                            </span>

                            About Me

                        </div>

                        <div
                            class="personal-value about-value"
                            id="valueAbout"
                        >
                            <?= e($user["about"]) ?>
                        </div>

                    </div>

                </div>

            </section>



            <!-- =================================================
                 RIGHT
            ================================================== -->



        </div>

    </div>

</main>



<!-- =====================================================
     EDIT PROFILE MODAL
===================================================== -->

<div
    id="editProfileModal"
    class="profile-modal"
>

    <div class="profile-modal-box">

        <div class="profile-modal-header">

            <h2>
                Edit Profile
            </h2>

            <button
                type="button"
                onclick="closeEditProfile()"
            >
                &times;
            </button>

        </div>


        <div class="profile-edit-form">

            <!-- Full Name -->
            <div class="form-group">

                <label for="editName">
                    Full Name
                </label>

                <input
                    type="text"
                    id="editName"
                    name="name"
                    value="<?= e($user["name"]) ?>"
                    required
                >

            </div>


            <!-- Email -->
            <div class="form-group">

                <label for="editEmail">
                    Email Address
                </label>

                <input
                    type="email"
                    id="editEmail"
                    name="email"
                    value="<?= e($user["email"]) ?>"
                    required
                >

            </div>


            <!-- Phone -->
            <div class="form-group">

                <label for="editPhone">
                    Phone Number
                </label>

                <input
                    type="text"
                    id="editPhone"
                    name="phone"
                    value="<?= e($user["phone"]) ?>"
                >

            </div>


            <!-- Date of Birth -->
            <div class="form-group">

                <label for="editBirthDate">
                    Date of Birth
                </label>

                <input
                    type="date"
                    id="editBirthDate"
                    name="birth_date"
                    value="<?= e($user["birth_date"]) ?>"
                >

            </div>


            <!-- Gender -->
            <div class="form-group">

                <label for="editGender">
                    Gender
                </label>

                <select
                    id="editGender"
                    name="gender"
                >

                    <option value="">
                        Select Gender
                    </option>

                    <option
                        value="Female"
                        <?= $user["gender"] === "Female" ? "selected" : "" ?>
                    >
                        Female
                    </option>

                    <option
                        value="Male"
                        <?= $user["gender"] === "Male" ? "selected" : "" ?>
                    >
                        Male
                    </option>

                </select>

            </div>


            <!-- Address -->
            <div class="form-group">

                <label for="editAddress">
                    Address
                </label>

                <input
                    type="text"
                    id="editAddress"
                    name="address"
                    value="<?= e($user["address"]) ?>"
                >

            </div>


            <!-- Language -->
            <div class="form-group">

                <label for="editLanguage">
                    Language
                </label>

                <select
                    id="editLanguage"
                    name="language"
                >

                    <option
                        value="English"
                        <?= $user["language"] === "English" ? "selected" : "" ?>
                    >
                        English
                    </option>

                    <option
                        value="Arabic"
                        <?= $user["language"] === "Arabic" ? "selected" : "" ?>
                    >
                        Arabic
                    </option>

                </select>

            </div>


            <!-- Time Zone -->
            <div class="form-group">

                <label for="editTimezone">
                    Time Zone
                </label>

                <select
                    id="editTimezone"
                    name="timezone"
                >

                    <option
                        value="(GMT+02:00) Cairo"
                        <?= $user["timezone"] === "(GMT+02:00) Cairo" ? "selected" : "" ?>
                    >
                        (GMT+02:00) Cairo
                    </option>

                    <option
                        value="(GMT+03:00) Riyadh"
                        <?= $user["timezone"] === "(GMT+03:00) Riyadh" ? "selected" : "" ?>
                    >
                        (GMT+03:00) Riyadh
                    </option>

                </select>

            </div>


            <!-- About Me -->
            <div class="form-group">

                <label for="editAbout">
                    About Me
                </label>

                <textarea
                    id="editAbout"
                    name="about"
                    rows="4"
                ><?= e($user["about"]) ?></textarea>

            </div>

        </div>


        <!-- Modal Buttons -->
        <div class="profile-modal-actions">

            <button
                type="button"
                class="modal-cancel-btn"
                onclick="closeEditProfile()"
            >
                Cancel
            </button>

            <button
                type="button"
                class="modal-save-btn"
                id="saveProfileBtn"
                onclick="saveEditProfile()"
            >
                Save Changes
            </button>

        </div>

    </div>

</div>

<!-- =====================================================
     CHANGE PASSWORD MODAL
===================================================== -->

<div
    id="changePasswordModal"
    class="profile-modal"
>

    <div class="profile-modal-box small-modal">

        <div class="profile-modal-header">

            <h2>
                Change Password
            </h2>

            <button
                type="button"
                onclick="closeSecurityBox('changePasswordModal')"
            >
                &times;
            </button>

        </div>


        <div class="profile-edit-form">

            <div class="form-group">

                <label>
                    Current Password
                </label>

                <input
                    type="password"
                    id="currentPassword"
                    placeholder="Enter current password"
                >

            </div>


            <div class="form-group">

                <label>
                    New Password
                </label>

                <input
                    type="password"
                    id="newPassword"
                    placeholder="Enter new password"
                >

            </div>


            <div class="form-group">

                <label>
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="confirmPassword"
                    placeholder="Confirm new password"
                >

            </div>

        </div>


        <div class="profile-modal-actions">

            <button
                type="button"
                class="modal-cancel-btn"
                onclick="closeSecurityBox('changePasswordModal')"
            >
                Cancel
            </button>

            <button
                type="button"
                class="modal-save-btn"
                onclick="savePassword()"
            >
                Save Changes
            </button>

        </div>

    </div>

</div>



<!-- =====================================================
     TWO FACTOR MODAL
===================================================== -->

<div
    id="twoFactorModal"
    class="profile-modal"
>

    <div class="profile-modal-box small-modal">

        <div class="profile-modal-header">

            <h2>
                Two-Factor Authentication
            </h2>

            <button
                type="button"
                onclick="closeSecurityBox('twoFactorModal')"
            >
                &times;
            </button>

        </div>


        <div class="two-factor-content">

            <div class="two-factor-icon">

                <i class="bi bi-shield-check"></i>

            </div>

            <h3>
                Two-Factor Authentication
            </h3>

            <p>
                Add an extra layer of security to your account.
            </p>


            <label class="two-factor-switch">

                <input
                    type="checkbox"
                    id="twoFactorSwitch"
                    <?= $user["two_factor_enabled"] ? "checked" : "" ?>
                >

                <span></span>

            </label>


            <strong id="twoFactorStatus">

                Two-Factor Authentication is
                <?= $user["two_factor_enabled"] ? "Enabled" : "Disabled" ?>

            </strong>

        </div>


        <div class="profile-modal-actions">

            <button
                type="button"
                class="modal-cancel-btn"
                onclick="closeSecurityBox('twoFactorModal')"
            >
                Close
            </button>

            <button
                type="button"
                class="modal-save-btn"
                onclick="saveTwoFactor()"
            >
                Save
            </button>

        </div>

    </div>

</div>



<!-- =====================================================
     LOGIN ACTIVITY MODAL
===================================================== -->

<div
    id="loginActivityModal"
    class="profile-modal"
>

    <div class="profile-modal-box small-modal">

        <div class="profile-modal-header">

            <h2>
                Login Activity
            </h2>

            <button
                type="button"
                onclick="closeSecurityBox('loginActivityModal')"
            >
                &times;
            </button>

        </div>


        <div class="login-activity-list">

            <div class="login-activity-item">

                <i class="bi bi-laptop"></i>

                <div>

                    <strong>
                        Current Device
                    </strong>

                    <small>
                        Current session
                    </small>

                </div>

                <span>
                    Current
                </span>

            </div>


            <div class="login-activity-item">

                <i class="bi bi-phone"></i>

                <div>

                    <strong>
                        Mobile Device
                    </strong>

                    <small>
                        Recent activity
                    </small>

                </div>

            </div>

        </div>


        <div class="profile-modal-actions">

            <button
                type="button"
                class="modal-cancel-btn"
                onclick="closeSecurityBox('loginActivityModal')"
            >
                Close
            </button>

        </div>

    </div>

</div>



<!-- =====================================================
     MANAGE DEVICES MODAL
===================================================== -->

<div
    id="manageDevicesModal"
    class="profile-modal"
>

    <div class="profile-modal-box small-modal">

        <div class="profile-modal-header">

            <h2>
                Manage Devices
            </h2>

            <button
                type="button"
                onclick="closeSecurityBox('manageDevicesModal')"
            >
                &times;
            </button>

        </div>


        <div class="devices-list">

            <div class="device-item">

                <div class="device-icon">

                    <i class="bi bi-laptop"></i>

                </div>

                <div class="device-info">

                    <strong>
                        Current Device
                    </strong>

                    <small>
                        Current browser session
                    </small>

                </div>

                <span class="device-current">
                    Current
                </span>

            </div>


            <div class="device-item">

                <div class="device-icon">

                    <i class="bi bi-phone"></i>

                </div>

                <div class="device-info">

                    <strong>
                        Mobile Device
                    </strong>

                    <small>
                        Connected device
                    </small>

                </div>

                <button
                    type="button"
                    class="remove-device"
                    onclick="removeDevice(this)"
                >
                    Remove
                </button>

            </div>

        </div>


        <div class="profile-modal-actions">

            <button
                type="button"
                class="modal-cancel-btn"
                onclick="closeSecurityBox('manageDevicesModal')"
            >
                Close
            </button>

        </div>

    </div>

</div>



<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- Main JS -->

<script src="../js/script.js"></script>


</body>

</html>