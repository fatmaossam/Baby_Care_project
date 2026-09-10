<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require_once "../php/config/database.php";

$user_id = (int) $_SESSION['user_id'];

/* ================= USER ================= */
$user = [
    'id' => $user_id,
    'name' => $_SESSION['user_name'] ?? 'User',
    'email' => $_SESSION['user_email'] ?? '',
    'profile_image' => ''
];

$stmt = $conn->prepare("SELECT id, name, email, profile_image FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user = $row;
}
$stmt->close();

/* ================= BABIES ================= */
$babies = [];

$stmt = $conn->prepare("
    SELECT id, name, birth_date, gender, birth_weight, birth_height, notes
    FROM babies
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $babies[] = $row;
}

$stmt->close();

$selectedBabyId = $_SESSION['selected_baby_id'] ?? ($babies[0]['id'] ?? null);

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatBabyDate($date): string {
    if (empty($date)) return '-';
    $time = strtotime($date);
    return $time ? date('M d, Y', $time) : e($date);
}

function babyAge($date): string {
    if (empty($date)) return '-';
    try {
        $birth = new DateTime($date);
        $today = new DateTime();
        if ($birth > $today) return '-';
        $age = $birth->diff($today);
        if ($age->y > 0) {
            return $age->y . ' ' . ($age->y === 1 ? 'year' : 'years');
        }
        if ($age->m > 0) {
            return $age->m . ' ' . ($age->m === 1 ? 'month' : 'months');
        }
        return $age->d . ' ' . ($age->d === 1 ? 'day' : 'days');
    } catch (Exception $e) {
        return '-';
    }
}

$avatar = !empty($user['profile_image'])
    ? '../' . ltrim($user['profile_image'], '/')
    : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baby Care - Baby Profile</title>
<!-- Bootstrap -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<!-- Main CSS -->
<link rel="stylesheet" href="../css/style.css">

<!-- Font Awesome -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
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
</head>

<body class="dashboard-page baby-profile-page">

<!-- ================= SIDEBAR ================= -->
<aside class="dashboard-sidebar">

    <div class="sidebar-logo">
        <a href="dashboard.php">
            <img src="../assets/icons/logo.png" alt="BabyCare">
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

        <a href="baby_profile.php" class="sidebar-link active">
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

    <div class="sidebar-bottom">
        <a href="../php/auth/logout.php" class="sidebar-link logout-link">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>

</aside>

<!-- ================= MAIN ================= -->
<main class="dashboard-main">

    <!-- TOPBAR -->
    <header class="dashboard-topbar">
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

        <div class="dashboard-top-actions">
            <!-- Notifications -->
            <div class="dropdown">
                <button class="notification-btn" id="notificationBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="notification-count">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-menu">
                    <div class="notification-header">
                        <strong>Notifications</strong>
                        <button type="button">Mark all as read</button>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon pink"><i class="bi bi-bandaid"></i></div>
                        <div>
                            <h6>Upcoming Vaccination</h6>
                            <p>Check your baby's vaccination schedule.</p>
                            <small>Today</small>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon yellow"><i class="bi bi-egg-fried"></i></div>
                        <div>
                            <h6>Try New Food</h6>
                            <p>It's a good time to introduce a new food.</p>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon blue"><i class="bi bi-lightbulb"></i></div>
                        <div>
                            <h6>New Tip</h6>
                            <p>A new baby care tip is waiting for you.</p>
                            <small>Yesterday</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User -->
            <div class="dropdown">
                <button class="user-account-btn" id="userBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        <?php if ($avatar): ?>
                            <img src="<?= e($avatar) ?>" alt="Profile">
                        <?php else: ?>
                            <?= e(strtoupper(substr($user['name'], 0, 1))) ?>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <strong><?= e($user['name']) ?></strong>
                        <span>Parent</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                    <li class="user-dropdown-name">
                        <strong><?= e($user['name']) ?></strong>
                        <span><?= e($user['email']) ?></span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item logout-dropdown" href="../php/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="dashboard-content baby-profile-content">
        <div class="baby-page-heading">
            <div>
                <span class="section-label">YOUR BABY</span>
                <h1>Baby Profile</h1>
                <p>Manage your baby's information and keep their profile updated.</p>
            </div>
            <div class="baby-heading-icon"><i class="bi bi-emoji-smile"></i></div>
        </div>

        <!-- Tabs -->
        <div class="baby-tabs">
            <button id="addTab" class="baby-tab active" type="button">
                <i class="bi bi-plus-circle"></i> Add New Baby
            </button>
            <button id="babiesTab" class="baby-tab" type="button">
                <i class="bi bi-people"></i> My Babies
            </button>
        </div>

        <!-- ADD NEW BABY -->
        <section id="addSection" class="baby-tab-section">
            <div class="baby-form-grid">
                <div class="baby-panel">
                    <div class="baby-panel-heading">
                        <div class="panel-icon pink"><i class="bi bi-person-plus"></i></div>
                        <div>
                            <h2>Add New Baby</h2>
                            <p>Enter the basic information for your baby.</p>
                        </div>
                    </div>

                    <label for="name">Full Name</label>
                    <div class="baby-input">
                        <i class="bi bi-person"></i>
                        <input type="text" id="name" placeholder="Enter baby's name">
                    </div>

                    <label for="date">Date of Birth</label>
                    <div class="baby-input">
                        <i class="bi bi-calendar3"></i>
                        <input type="date" id="date">
                    </div>

                    <label>Gender</label>
                    <div class="gender-buttons">
                        <button type="button" id="boy" class="gender-btn boy">
                            <i class="fa-solid fa-mars"></i><span>Boy</span>
                        </button>
                        <button type="button" id="girl" class="gender-btn girl">
                            <i class="fa-solid fa-venus"></i><span>Girl</span>
                        </button>
                    </div>

                    <label for="weight">Birth Weight <small>(kg)</small></label>
                    <div class="baby-input">
                        <i class="bi bi-speedometer2"></i>
                        <input type="number" id="weight" step="0.01" min="0" placeholder="e.g. 3.20">
                    </div>

                    <label for="height">Birth Height <small>(cm)</small></label>
                    <div class="baby-input">
                        <i class="bi bi-rulers"></i>
                        <input type="number" id="height" step="0.01" min="0" placeholder="e.g. 50">
                    </div>

                    <label for="notes">Notes <small>(Optional)</small></label>
                    <div class="baby-textarea">
                        <i class="bi bi-sticky"></i>
                        <textarea id="notes" placeholder="Any notes about your baby..."></textarea>
                    </div>

                    <button id="save" class="baby-save-btn" type="button">
                        <i class="bi bi-check2-circle"></i> Save Baby
                    </button>
                </div>

                <!-- PREVIEW -->
                
                
            </div>
        </section>

        <!-- MY BABIES -->
        <section id="babiesSection" class="baby-tab-section" hidden>
            <div class="my-babies-header">
                <div>
                    <span class="section-label">YOUR PROFILES</span>
                    <h2>My Babies</h2>
                    <p>All baby profiles connected to your account.</p>
                </div>
                <button type="button" class="add-another-baby" onclick="document.getElementById('addTab')?.click()">
                    <i class="bi bi-plus"></i> Add Baby
                </button>
            </div>

            <div id="babiesList" class="babies-grid">
                <?php if (empty($babies)): ?>
                    <div class="baby-empty-state">
                        <div class="empty-icon"><i class="bi bi-emoji-smile"></i></div>
                        <h3>No baby profile yet</h3>
                        <p>Add your first baby profile to start tracking their care.</p>
                        <button type="button" class="add-another-baby" onclick="document.getElementById('addTab')?.click()">
                            <i class="bi bi-plus"></i> Add Your First Baby
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($babies as $index => $baby): ?>
                        <?php $isCurrentBaby = ((int)$baby['id'] === (int)$selectedBabyId); ?>
                        <article
    class="saved-baby-card <?= $isCurrentBaby ? 'current-baby' : '' ?>"
    data-baby-id="<?= (int)$baby['id'] ?>"
    data-name="<?= e($baby['name']) ?>"
    data-birth-date="<?= e($baby['birth_date']) ?>"
    data-gender="<?= e($baby['gender']) ?>"
    data-birth-weight="<?= e($baby['birth_weight'] ?? '') ?>"
    data-birth-height="<?= e($baby['birth_height'] ?? '') ?>"
    data-notes="<?= e($baby['notes'] ?? '') ?>"
>
                            <div class="saved-baby-top">
                                <div class="saved-baby-avatar">
                                    <i class="bi bi-emoji-smile"></i>
                                </div>
                                <div class="saved-baby-title">
                                    <div class="saved-baby-name-row">
                                        <h3><?= e($baby['name']) ?></h3>
                                        <?php if ($isCurrentBaby): ?><span class="current-badge">Current</span><?php endif; ?>
                                    </div>
                                    <span><?= e(ucfirst($baby['gender'])) ?> · <?= e(babyAge($baby['birth_date'])) ?> old</span>
                                </div>
                                <button
    type="button"
    class="baby-more-btn"
    data-baby-id="<?= (int)$baby['id'] ?>"
    title="More options"
>
    <i class="bi bi-three-dots"></i>
</button>
                            </div>

                            <div class="saved-baby-info">
                                <div><i class="bi bi-calendar3"></i><span>Birth Date</span><strong><?= e(formatBabyDate($baby['birth_date'])) ?></strong></div>
                                <div><i class="bi bi-gender-ambiguous"></i><span>Gender</span><strong><?= e(ucfirst($baby['gender'])) ?></strong></div>
                                <div><i class="bi bi-speedometer2"></i><span>Birth Weight</span><strong><?= $baby['birth_weight'] !== null ? e($baby['birth_weight']) . ' kg' : '—' ?></strong></div>
                                <div><i class="bi bi-rulers"></i><span>Birth Height</span><strong><?= $baby['birth_height'] !== null ? e($baby['birth_height']) . ' cm' : '—' ?></strong></div>
                            </div>

                            <div class="saved-baby-notes">
                                <i class="bi bi-sticky"></i>
                                <div><span>Notes</span><p><?= !empty($baby['notes']) ? e($baby['notes']) : 'No notes added yet.' ?></p></div>
                            </div>

                            <div class="saved-baby-footer">
                                <span><i class="bi bi-heart"></i> Baby Care</span>
                                <button type="button" class="view-baby-btn">View Profile <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
