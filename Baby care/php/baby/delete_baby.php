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
   GET JSON DATA
========================= */

$input = json_decode(
    file_get_contents("php://input"),
    true
);

$baby_id = isset($input["id"])
    ? (int)$input["id"]
    : 0;


if ($baby_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid baby ID."
    ]);

    exit;
}


/* =========================
   CHECK OWNERSHIP
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
   DELETE
========================= */

$stmt = $conn->prepare("
    DELETE FROM babies
    WHERE id = ?
      AND user_id = ?
");

$stmt->bind_param(
    "ii",
    $baby_id,
    $user_id
);


if (!$stmt->execute()) {

    /*
       Because our database relationships use
       ON DELETE RESTRICT, deletion can fail
       if this baby already has related records.
    */

    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "This baby cannot be deleted because they have related records."
    ]);

    exit;
}


$stmt->close();


/* =========================
   RESET SELECTED BABY
========================= */

if (
    isset($_SESSION["selected_baby_id"]) &&
    (int)$_SESSION["selected_baby_id"] === $baby_id
) {
    unset($_SESSION["selected_baby_id"]);
}


/* =========================
   SUCCESS
========================= */

echo json_encode([
    "success" => true,
    "message" => "Baby profile deleted successfully."
]);

exit;