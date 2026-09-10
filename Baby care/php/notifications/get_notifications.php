<?php

session_start();

require_once "../config/database.php";

header("Content-Type: application/json");

if (empty($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$notifications = [];
$seen_notifications = $_SESSION["seen_notifications"] ?? [];

/* =========================================================
   GET SELECTED BABY
========================================================= */

$selected_baby_id = $_SESSION["selected_baby_id"] ?? null;

if ($selected_baby_id) {

    $stmt = $conn->prepare("
        SELECT id, name
        FROM babies
        WHERE id = ?
        AND user_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("ii", $selected_baby_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $baby = $result->fetch_assoc();

    $stmt->close();

} else {

    $stmt = $conn->prepare("
        SELECT id, name
        FROM babies
        WHERE user_id = ?
        ORDER BY id ASC
        LIMIT 1
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $baby = $result->fetch_assoc();

    $stmt->close();

    if ($baby) {
        $_SESSION["selected_baby_id"] = (int) $baby["id"];
    }
}


/* =========================================================
   NO BABY
========================================================= */

if (!$baby) {

    echo json_encode([
        "success" => true,
        "count" => 0,
        "notifications" => []
    ]);

    exit;
}

$baby_id = (int) $baby["id"];
$baby_name = $baby["name"];


/* =========================================================
   1. OVERDUE VACCINATION
========================================================= */

$stmt = $conn->prepare("
    SELECT v.name, bv.due_date
    FROM baby_vaccinations bv
    INNER JOIN vaccines v
        ON bv.vaccine_id = v.id
    WHERE bv.baby_id = ?
    AND bv.date_taken IS NULL
    AND bv.due_date < CURDATE()
    ORDER BY bv.due_date ASC
    LIMIT 1
");

$stmt->bind_param("i", $baby_id);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    $notifications[] = [
        "type" => "overdue",
        "icon" => "bi-bandaid",
        "color" => "pink",
        "title" => "Overdue Vaccination",
        "message" => $baby_name . " has an overdue vaccination.",
        "time" => "Needs attention",
        "link" => "vaccination.php"
    ];
}

$stmt->close();


/* =========================================================
   2. UPCOMING VACCINATION
========================================================= */

$stmt = $conn->prepare("
    SELECT v.name, bv.due_date
    FROM baby_vaccinations bv
    INNER JOIN vaccines v
        ON bv.vaccine_id = v.id
    WHERE bv.baby_id = ?
    AND bv.date_taken IS NULL
    AND bv.due_date >= CURDATE()
    ORDER BY bv.due_date ASC
    LIMIT 1
");

$stmt->bind_param("i", $baby_id);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    $due_date = new DateTime($row["due_date"]);
    $today = new DateTime();

    $days = (int) $today->diff($due_date)->format("%r%a");

    if ($days === 0) {
        $time_text = "Today";
    } elseif ($days === 1) {
        $time_text = "Tomorrow";
    } else {
        $time_text = "In " . $days . " days";
    }

    $notifications[] = [
        "type" => "vaccination",
        "icon" => "bi-bandaid",
        "color" => "pink",
        "title" => "Upcoming Vaccination",
        "message" => $baby_name . " has " . $row["name"] . " coming up.",
        "time" => $time_text,
        "link" => "vaccination.php"
    ];
}

$stmt->close();


/* =========================================================
   3. NEW FOOD SUGGESTION
========================================================= */

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

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    $notifications[] = [
        "type" => "food",
        "icon" => "bi-egg-fried",
        "color" => "yellow",
        "title" => "Try New Food",
        "message" => "You can try introducing " . $row["name"] . " to " . $baby_name . ".",
        "time" => "Suggested",
        "link" => "foods.php"
    ];
}

$stmt->close();


/* =========================================================
   RESPONSE
========================================================= */

echo json_encode([
    "success" => true,
    "count" => count($notifications),
    "notifications" => $notifications
]);