<?php

session_start();

require_once "../config/database.php";


// Protect page
if (!isset($_SESSION["user_id"])) {
    die("You must be logged in.");
}

$user_id = $_SESSION["user_id"];


// Only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}


// Get data
$record_id = intval($_POST["record_id"] ?? 0);
$baby_id = intval($_POST["baby_id"] ?? 0);

$record_type = $_POST["record_type"] ?? "";
$record_date = $_POST["record_date"] ?? date("Y-m-d");
$time = $_POST["time"] ?? "";


// Validate
if ($record_id <= 0 || $baby_id <= 0) {
    die("Invalid record or baby.");
}


// Make sure baby belongs to current user
$stmt = $conn->prepare("
    SELECT id
    FROM babies
    WHERE id = ?
    AND user_id = ?
");

$stmt->bind_param("ii", $baby_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Invalid baby.");
}

$stmt->close();


// Make sure record belongs to this baby
$stmt = $conn->prepare("
    SELECT id
    FROM daily_records
    WHERE id = ?
    AND baby_id = ?
");

$stmt->bind_param("ii", $record_id, $baby_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Invalid record.");
}

$stmt->close();


// Normalize record type
if ($record_type === "sleep") {
    $record_type = "Sleep";
} elseif ($record_type === "feeding") {
    $record_type = "Feeding";
} elseif ($record_type === "diaper") {
    $record_type = "Diaper";
} elseif ($record_type === "note") {
    $record_type = "Notes";
} else {
    die("Invalid record type.");
}


// Variables
$details = "";
$start_time = null;
$end_time = null;
$notes = $_POST["additional_notes"] ?? "";


// =========================
// SLEEP
// =========================

if ($record_type === "Sleep") {

    $sleep_start = $_POST["sleep_start"] ?? "";
    $sleep_end = $_POST["sleep_end"] ?? "";

    if (empty($sleep_start) || empty($sleep_end)) {
        die("Please enter sleep start and end time.");
    }

    $start_time = $record_date . " " . $sleep_start . ":00";
    $end_time = $record_date . " " . $sleep_end . ":00";

    $start = new DateTime($start_time);
    $end = new DateTime($end_time);

    $duration = $start->diff($end);

    $hours = $duration->h;
    $minutes = $duration->i;

    if ($hours > 0 && $minutes > 0) {

        $details = "Slept for "
            . $hours . " hour" . ($hours > 1 ? "s" : "")
            . " "
            . $minutes . " minute" . ($minutes > 1 ? "s" : "");

    } elseif ($hours > 0) {

        $details = "Slept for "
            . $hours . " hour" . ($hours > 1 ? "s" : "");

    } else {

        $details = "Slept for "
            . $minutes . " minute" . ($minutes > 1 ? "s" : "");
    }
}


// =========================
// FEEDING
// =========================

elseif ($record_type === "Feeding") {

    $feeding_type = $_POST["feeding_type"] ?? "";
    $quantity = trim($_POST["quantity"] ?? "");
    $reaction = trim($_POST["reaction"] ?? "");

    if (empty($feeding_type)) {
        die("Please select feeding type.");
    }

    $details = $feeding_type;

    if ($quantity !== "") {
        $details .= " - " . $quantity;
    }

    if ($reaction !== "") {
        $notes .= ($notes !== "" ? "\n" : "")
            . "Reaction: " . $reaction;
    }

    if (empty($time)) {
        die("Please enter time.");
    }

    $start_time = $record_date . " " . $time . ":00";
}


// =========================
// DIAPER
// =========================

elseif ($record_type === "Diaper") {

    $diaper_type = $_POST["diaper_type"] ?? "";

    if (empty($diaper_type)) {
        die("Please select diaper type.");
    }

    $details = $diaper_type;

    if (empty($time)) {
        die("Please enter time.");
    }

    $start_time = $record_date . " " . $time . ":00";
}


// =========================
// NOTES
// =========================

elseif ($record_type === "Notes") {

    $note_text = trim($_POST["note_text"] ?? "");

    if (empty($note_text)) {
        die("Please enter a note.");
    }

    $details = $note_text;

    if (empty($time)) {
        die("Please enter time.");
    }

    $start_time = $record_date . " " . $time . ":00";
}


// =========================
// UPDATE RECORD
// =========================

$stmt = $conn->prepare("
    UPDATE daily_records
    SET
        record_type = ?,
        details = ?,
        start_time = ?,
        end_time = ?,
        notes = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
    AND baby_id = ?
");

$stmt->bind_param(
    "sssssii",
    $record_type,
    $details,
    $start_time,
    $end_time,
    $notes,
    $record_id,
    $baby_id
);


if ($stmt->execute()) {

    header("Location: ../../html/daily-tracker.php");
    exit;

} else {

    die("Failed to update record.");
}

?>