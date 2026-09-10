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

$user_id = (int)$_SESSION["user_id"];

$baby_id = filter_input(INPUT_POST, "baby_id", FILTER_VALIDATE_INT);

if (!$baby_id) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid baby"
    ]);
    exit;
}

// Make sure this baby belongs to the logged-in user
$stmt = $conn->prepare("
    SELECT id, name, birth_date, gender
    FROM babies
    WHERE id = ?
    AND user_id = ?
    LIMIT 1
");

$stmt->bind_param("ii", $baby_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "success" => false,
        "message" => "Baby not found"
    ]);
    exit;
}

$baby = $result->fetch_assoc();

$stmt->close();

// Save selected baby in session
$_SESSION["selected_baby_id"] = (int)$baby["id"];

echo json_encode([
    "success" => true,
    "baby" => $baby
]);