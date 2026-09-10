<?php

session_start();

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');


/* =========================
   CHECK LOGIN
========================= */

if (empty($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'User not logged in.'
    ]);

    exit;
}


$user_id = (int) $_SESSION['user_id'];


/* =========================
   GET USER BABIES
========================= */

$sql = "
    SELECT
        id,
        name,
        birth_date,
        gender,
        birth_weight,
        birth_height,
        notes
    FROM babies
    WHERE user_id = ?
    ORDER BY id DESC
";


$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode([
        'success' => false,
        'message' => 'Database prepare failed.'
    ]);

    exit;
}


$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();


$babies = [];


while ($baby = $result->fetch_assoc()) {

    $babies[] = $baby;
}


$stmt->close();


/* =========================
   RESPONSE
========================= */

echo json_encode([
    'success' => true,
    'babies' => $babies
]);

exit;