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


$current_password =
    $input["current_password"] ?? "";

$new_password =
    $input["new_password"] ?? "";

$confirm_password =
    $input["confirm_password"] ?? "";


/* =========================
   VALIDATION
========================= */

if (
    $current_password === "" ||
    $new_password === "" ||
    $confirm_password === ""
) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Please fill all password fields."
    ]);

    exit;
}


if ($new_password !== $confirm_password) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "New passwords do not match."
    ]);

    exit;
}


if (strlen($new_password) < 8) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" =>
            "New password must be at least 8 characters."
    ]);

    exit;
}


/* =========================
   GET CURRENT PASSWORD
========================= */

$stmt = $conn->prepare("
    SELECT password
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


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
   VERIFY OLD PASSWORD
========================= */

if (
    !password_verify(
        $current_password,
        $user["password"]
    )
) {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Current password is incorrect."
    ]);

    exit;
}


/* =========================
   HASH NEW PASSWORD
========================= */

$new_hash = password_hash(
    $new_password,
    PASSWORD_DEFAULT
);


/* =========================
   UPDATE PASSWORD
========================= */

$stmt = $conn->prepare("
    UPDATE users
    SET
        password = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");

$stmt->bind_param(
    "si",
    $new_hash,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to change password."
    ]);

    exit;
}


$stmt->close();


/* =========================
   SUCCESS
========================= */

echo json_encode([
    "success" => true,
    "message" => "Password changed successfully."
]);

?>