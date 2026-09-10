<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../html/settings.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../html/settings.php");
    exit;
}

$email_notifications = isset($_POST["email_notifications"]) ? 1 : 0;
$push_notifications = isset($_POST["push_notifications"]) ? 1 : 0;
$tips_reminders = isset($_POST["tips_reminders"]) ? 1 : 0;

$stmt = $conn->prepare("
    UPDATE users
    SET
        email_notifications = ?,
        push_notifications = ?,
        tips_reminders = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");

$stmt->bind_param(
    "iiii",
    $email_notifications,
    $push_notifications,
    $tips_reminders,
    $user_id
);

if ($stmt->execute()) {
    $stmt->close();

    header("Location: ../../html/settings.php?notifications=success");
    exit;
}

$stmt->close();

die("Failed to update notification preferences.");

?>