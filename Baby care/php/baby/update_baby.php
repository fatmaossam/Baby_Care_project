<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once "../config/database.php";


/* =========================
   CHECK LOGIN
========================= */

if (empty($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in."
    ]);

    exit;
}


$user_id = (int) $_SESSION["user_id"];


/* =========================
   GET DATA
========================= */

$baby_id = isset($_POST["id"])
    ? (int) $_POST["id"]
    : 0;

$name = trim($_POST["name"] ?? "");

$birth_date = $_POST["birth_date"] ?? "";

$gender = trim($_POST["gender"] ?? "");

$birth_weight = $_POST["birth_weight"] ?? "";

$birth_height = $_POST["birth_height"] ?? "";

$notes = trim($_POST["notes"] ?? "");


/* =========================
   VALIDATION
========================= */

$errors = [];


if ($baby_id <= 0) {
    $errors["id"] = "Invalid baby ID.";
}


if ($name === "") {
    $errors["name"] = "Baby name is required.";
} elseif (mb_strlen($name) > 100) {
    $errors["name"] = "Baby name is too long.";
}


if ($birth_date === "") {

    $errors["birth_date"] = "Birth date is required.";

} else {

    $date = DateTime::createFromFormat("Y-m-d", $birth_date);

    if (!$date || $date->format("Y-m-d") !== $birth_date) {

        $errors["birth_date"] = "Invalid birth date.";

    } elseif ($birth_date > date("Y-m-d")) {

        $errors["birth_date"] = "Birth date cannot be in the future.";
    }
}


$gender = ucfirst(strtolower($gender));

if (!in_array($gender, ["Boy", "Girl"], true)) {

    $errors["gender"] = "Please select a valid gender.";
}


if ($birth_weight !== "") {

    if (!is_numeric($birth_weight) || (float)$birth_weight < 0) {

        $errors["birth_weight"] = "Invalid birth weight.";
    }
}


if ($birth_height !== "") {

    if (!is_numeric($birth_height) || (float)$birth_height < 0) {

        $errors["birth_height"] = "Invalid birth height.";
    }
}


if (!empty($errors)) {

    echo json_encode([
        "success" => false,
        "errors" => $errors
    ]);

    exit;
}


/* =========================
   CHECK BABY BELONGS TO USER
========================= */

$stmt = $conn->prepare("
    SELECT id
    FROM babies
    WHERE id = ?
      AND user_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "ii",
    $baby_id,
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "Baby profile not found."
    ]);

    exit;
}

$stmt->close();


/* =========================
   PREPARE NULL VALUES
========================= */

$weight_value = ($birth_weight === "")
    ? null
    : (float)$birth_weight;

$height_value = ($birth_height === "")
    ? null
    : (float)$birth_height;

$notes_value = ($notes === "")
    ? null
    : $notes;


/* =========================
   UPDATE BABY
========================= */

$stmt = $conn->prepare("
    UPDATE babies
    SET
        name = ?,
        birth_date = ?,
        gender = ?,
        birth_weight = ?,
        birth_height = ?,
        notes = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
      AND user_id = ?
");

$stmt->bind_param(
    "sssddsii",
    $name,
    $birth_date,
    $gender,
    $weight_value,
    $height_value,
    $notes_value,
    $baby_id,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "Failed to update baby profile."
    ]);

    exit;
}


$stmt->close();


/* =========================
   SUCCESS
========================= */

echo json_encode([
    "success" => true,
    "message" => "Baby profile updated successfully."
]);

exit;