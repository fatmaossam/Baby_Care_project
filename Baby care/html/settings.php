<?php

session_start();

require_once "../php/config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        email_notifications,
        push_notifications,
        tips_reminders
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
$user_phone = $user["phone"] ?? "";

?>





<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Settings | BabyCare</title>

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


<body class="settings-page">


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
                class="sidebar-link active"
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
                    placeholder="Search settings..."
                >

            </div>


            <div class="dashboard-top-actions">


                <!-- =================================================
                     NOTIFICATIONS
                ================================================== -->

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
                                    Adam has a vaccination coming up.
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



                <!-- =================================================
                     USER ACCOUNT
                ================================================== -->

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
             SETTINGS CONTENT
        ================================================== -->

        <div class="settings-content">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <section class="settings-header">

                <div>

                    <span class="settings-label">
                        ACCOUNT & PREFERENCES
                    </span>

                    <h1>
                        Settings
                    </h1>

                    <p>
                        Manage your account preferences and settings.
                    </p>

                </div>

            </section>



            <!-- =================================================
                 SETTINGS GRID
            ================================================== -->

            <div class="settings-grid">


                <!-- =================================================
                     ACCOUNT INFORMATION
                ================================================== -->

                <section class="settings-card account-settings-card">


                    <div class="settings-card-header">


                        <div class="settings-heading">


                            <div class="settings-heading-icon">

                                <i class="bi bi-person"></i>

                            </div>


                            <div>

                                <h2>
                                    Account Information
                                </h2>

                                <p>
                                    Update your personal details.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="settings-outline-btn"
                            id="editAccountBtn"
                        >

                            <i class="bi bi-pencil"></i>

                            <span>
                                Edit
                            </span>

                        </button>

                    </div>



                    <form
                        class="account-form"
                        id="accountForm"
                    >


                        <div class="settings-field">

                            <label for="settingsName">
                                Name
                            </label>

                            <input
                                type="text"
                                id="settingsName"
                                value="<?= htmlspecialchars($user_name) ?>"
                                disabled
                            >

                        </div>


                        <div class="settings-field">

                            <label for="settingsEmail">
                                Email
                            </label>

                            <input
    type="email"
    id="settingsEmail"
    value="<?= htmlspecialchars($user_email) ?>"
    disabled
>

                        </div>


                        <div class="settings-field">

                            <label for="settingsPhone">
                                Phone
                            </label>

                            <input
                                type="tel"
                                id="settingsPhone"
                                value="<?= htmlspecialchars($user_phone) ?>"
                                disabled
                            >

                        </div>


                        <div
                            class="account-form-actions"
                            id="accountFormActions"
                        >

                            <button
                                type="button"
                                class="settings-cancel-btn"
                                id="cancelAccountBtn"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="settings-primary-btn"
                            >
                                Save Changes
                            </button>

                        </div>


                    </form>

                </section>



                <!-- =================================================
                     SECURITY
                ================================================== -->

                <section class="settings-card security-settings-card">


                    <div class="settings-card-header">

                        <div class="settings-heading">

                            <div class="settings-heading-icon security-icon">

                                <i class="bi bi-shield-lock"></i>

                            </div>


                            <div>

                                <h2>
                                    Security
                                </h2>

                                <p>
                                    Keep your account secure.
                                </p>

                            </div>

                        </div>

                    </div>



                    <div class="security-content">


                        <div class="security-row">

                            <div class="security-row-icon">

                                <i class="bi bi-key"></i>

                            </div>


                            <div class="security-row-text">

                                <strong>
                                    Password
                                </strong>

                                <span>
                                    Update your account password.
                                </span>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="settings-secondary-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal"
                        >

                            Change Password

                            <i class="bi bi-chevron-right"></i>

                        </button>

                    </div>

                </section>



                <!-- =================================================
                     NOTIFICATION PREFERENCES
                ================================================== -->

                <section class="settings-card notification-settings-card">


                    <div class="settings-card-header">


                        <div class="settings-heading">


                            <div class="settings-heading-icon">

                                <i class="bi bi-bell"></i>

                            </div>


                            <div>

                                <h2>
                                    Notification Preferences
                                </h2>

                                <p>
                                    Choose what you want to be notified about.
                                </p>

                            </div>

                        </div>

                    </div>

                    <form
    action="../php/settings/update_notifications.php"
    method="POST"
>



                    <div class="notification-settings-list">


                        <!-- Email -->

                        <div class="notification-setting-row">

                            <div class="notification-setting-icon">

                                <i class="bi bi-envelope"></i>

                            </div>


                            <div class="notification-setting-info">

                                <strong>
                                    Email Notifications
                                </strong>

                                <span>
                                    Receive important updates and reminders by email.
                                </span>

                            </div>


                            <label class="settings-switch">

                                <input
    type="checkbox"
    id="emailNotifications"
    name="email_notifications"
    value="1"
    <?= $user["email_notifications"] ? "checked" : "" ?>
>

                                <span class="settings-slider"></span>

                            </label>

                        </div>



                        <!-- Push -->

                        <div class="notification-setting-row">

                            <div class="notification-setting-icon">

                                <i class="bi bi-phone"></i>

                            </div>


                            <div class="notification-setting-info">

                                <strong>
                                    Push Notifications
                                </strong>

                                <span>
                                    Receive notifications and reminders on your device.
                                </span>

                            </div>


                            <label class="settings-switch">

                                <input
    type="checkbox"
    id="pushNotifications"
    name="push_notifications"
    value="1"
    <?= $user["push_notifications"] ? "checked" : "" ?>
>

                                <span class="settings-slider"></span>

                            </label>

                        </div>



                        <!-- Tips -->

                        <div class="notification-setting-row">

                            <div class="notification-setting-icon">

                                <i class="bi bi-lightbulb"></i>

                            </div>


                            <div class="notification-setting-info">

                                <strong>
                                    Tips & Reminders
                                </strong>

                                <span>
                                    Receive baby care tips and helpful reminders.
                                </span>

                            </div>


                            <label class="settings-switch">

                                <input
    type="checkbox"
    id="tipsReminders"
    name="tips_reminders"
    value="1"
    <?= $user["tips_reminders"] ? "checked" : "" ?>
>

                                <span class="settings-slider"></span>

                            </label>

                        </div>

                    </div>
                    <div class="notification-form-actions">

    <button
        type="submit"
        class="settings-primary-btn"
    >
        Save Preferences
    </button>

</div>

</form>

                </section>


            </div>

        </div>

    </main>



    <!-- =====================================================
         CHANGE PASSWORD MODAL
    ====================================================== -->

    <div
        class="modal fade settings-password-modal"
        id="changePasswordModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <span class="settings-modal-label">
                            SECURITY
                        </span>

                        <h2 class="modal-title">
                            Change Password
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
    id="changePasswordForm"
    action="../php/settings/change_password.php"
    method="POST"
>


                    <div class="modal-body">


                        <div class="password-field">

                            <label for="currentPassword">
                                Current Password
                            </label>

                            <div class="password-input">

                                <input
                    type="password"
                    id="currentPassword"
                    name="current_password"
                    required
                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="currentPassword"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        <div class="password-field">

                            <label for="newPassword">
                                New Password
                            </label>

                            <div class="password-input">

                                <input
    type="password"
    id="newPassword"
    name="new_password"
    required
    minlength="8"
>

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="newPassword"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        <div class="password-field">

                            <label for="confirmPassword">
                                Confirm New Password
                            </label>

                            <div class="password-input">

                                <input
    type="password"
    id="confirmPassword"
    name="confirm_password"
    required
    minlength="8"
>

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="confirmPassword"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        <p
                            class="password-error"
                            id="passwordError"
                        ></p>


                    </div>



                    <div class="modal-footer">

                        <button
                            type="button"
                            class="settings-cancel-btn"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="settings-primary-btn"
                        >
                            Update Password
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

    <script
        src="../js/script.js"
    ></script>

</body>

</html>