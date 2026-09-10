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

$baby_id = intval($_POST["baby_id"] ?? 0);
$food_id = intval($_POST["food_id"] ?? 0);
$reaction_type = trim($_POST["reaction_type"] ?? "");
$notes = trim($_POST["notes"] ?? "");

if ($baby_id <= 0) {
    die("Please select a baby.");
}

if ($food_id <= 0) {
    die("Please select a food.");
}

if ($reaction_type === "") {
    die("Please select a reaction.");
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

/* Check that the food exists */
$stmt = $conn->prepare("
    SELECT id
    FROM foods
    WHERE id = ?
");

$stmt->bind_param("i", $food_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Invalid food.");
}

$stmt->close();

/* Save allergy */
$stmt = $conn->prepare("
    INSERT INTO baby_allergies
    (
        baby_id,
        food_id,
        reaction_type,
        notes
    )
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param(
    "iiss",
    $baby_id,
    $food_id,
    $reaction_type,
    $notes
);

if ($stmt->execute()) {

    header("Location: ../../html/foods.php");
    exit;

} else {

    die("Failed to save allergy.");

}

?>