<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in.");
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$record_id = intval($_POST["record_id"] ?? 0);
$baby_id = intval($_POST["baby_id"] ?? 0);

if ($record_id <= 0 || $baby_id <= 0) {
    die("Invalid record or baby.");
}

/* Check that the baby belongs to the logged-in user */
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

/* Check that the record belongs to this baby */
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

/* Delete the record */
$stmt = $conn->prepare("
    DELETE FROM daily_records
    WHERE id = ?
    AND baby_id = ?
");

$stmt->bind_param("ii", $record_id, $baby_id);

if ($stmt->execute()) {
    header("Location: ../../html/daily-tracker.php");
    exit;
} else {
    die("Failed to delete record.");
}

?>