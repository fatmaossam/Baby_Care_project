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


if (!array_key_exists(
    "enabled",
    $input ?? []
)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Missing data."
    ]);

    exit;
}


$enabled = filter_var(
    $input["enabled"],
    FILTER_VALIDATE_BOOLEAN
);


/* =========================
   UPDATE
========================= */

$stmt = $conn->prepare("
    UPDATE users
    SET
        two_factor_enabled = ?,
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
        "message" => "Failed to update two-factor authentication."
    ]);

    exit;
}


$stmt->close();


/* =========================
   SUCCESS
========================= */

echo json_encode([

    "success" => true,

    "enabled" => $enabled,

    "message" => $enabled
        ? "Two-factor authentication enabled."
        : "Two-factor authentication disabled."

]);

?>