<?php

session_start();

header("Content-Type: application/json");

if (empty($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$notification_id = $_POST["notification_id"] ?? "";

if ($notification_id === "") {
    echo json_encode([
        "success" => false,
        "message" => "Notification ID is required"
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Store seen notifications in session
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["seen_notifications"])) {
    $_SESSION["seen_notifications"] = [];
}

if (!in_array($notification_id, $_SESSION["seen_notifications"], true)) {
    $_SESSION["seen_notifications"][] = $notification_id;
}


echo json_encode([
    "success" => true
]);