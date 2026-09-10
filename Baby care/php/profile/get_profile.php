<?php

session_start();

require_once "../config/database.php";

header("Content-Type: application/json; charset=utf-8");


/* =========================
   CHECK LOGIN
========================= */

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


$user_id = $_SESSION["user_id"];


/* =========================
   GET USER
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


/* =========================
   USER NOT FOUND
========================= */

if ($result->num_rows !== 1) {

    $stmt->close();

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);

    exit;
}


$user = $result->fetch_assoc();

$stmt->close();


/* =========================
   BOOLEAN VALUES
========================= */

$user["two_factor_enabled"] =
    (bool) $user["two_factor_enabled"];

$user["email_notifications"] =
    (bool) $user["email_notifications"];

$user["push_notifications"] =
    (bool) $user["push_notifications"];

$user["tips_reminders"] =
    (bool) $user["tips_reminders"];


/* =========================
   RESPONSE
========================= */

echo json_encode([
    "success" => true,
    "user" => $user
]);

?>