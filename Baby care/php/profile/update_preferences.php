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
   CHECK METHOD
========================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed."
    ]);

    exit;
}


/* =========================
   READ JSON
========================= */

$input = json_decode(
    file_get_contents("php://input"),
    true
);


$key = $input["key"] ?? "";

$enabled = filter_var(
    $input["enabled"] ?? false,
    FILTER_VALIDATE_BOOLEAN
);


/* =========================
   ALLOWED COLUMNS
========================= */

$allowedColumns = [

    "email_notifications",

    "push_notifications",

    "tips_reminders"

];


if (!in_array(
    $key,
    $allowedColumns,
    true
)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Unknown preference."
    ]);

    exit;
}


/* =========================
   UPDATE
========================= */

$stmt = $conn->prepare("
    UPDATE users
    SET {$key} = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");


$value = $enabled ? 1 : 0;


$stmt->bind_param(
    "ii",
    $value,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update preference."
    ]);

    exit;
}


$stmt->close();


/* =========================
   SUCCESS
========================= */

echo json_encode([

    "success" => true,

    "key" => $key,

    "enabled" => $enabled

]);

?>