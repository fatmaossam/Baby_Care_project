<?php

session_start();

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in.'
    ]);
    exit;
}

require_once "../config/database.php";

$user_id = $_SESSION['user_id'];

// استقبال البيانات
$name = trim($_POST['name'] ?? '');
$birth_date = trim($_POST['birth_date'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$birth_weight = $_POST['birth_weight'] ?? null;
$birth_height = $_POST['birth_height'] ?? null;
$notes = trim($_POST['notes'] ?? '');

// Validation
if ($name === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Baby name is required.'
    ]);
    exit;
}

if ($birth_date === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Birth date is required.'
    ]);
    exit;
}

if ($gender === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Gender is required.'
    ]);
    exit;
}

// Insert baby
$stmt = $conn->prepare("
    INSERT INTO babies
    (
        user_id,
        name,
        birth_date,
        gender,
        birth_weight,
        birth_height,
        notes,
        created_at,
        updated_at
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$stmt->bind_param(
    "isssdds",
    $user_id,
    $name,
    $birth_date,
    $gender,
    $birth_weight,
    $birth_height,
    $notes
);

if ($stmt->execute()) {

    $baby_id = $stmt->insert_id;

    echo json_encode([
        'success' => true,
        'message' => 'Baby added successfully.',
        'baby_id' => $baby_id
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Failed to add baby.'
    ]);
}

$stmt->close();
$conn->close();