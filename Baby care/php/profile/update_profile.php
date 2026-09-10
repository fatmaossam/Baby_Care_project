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


if (!is_array($input)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid data."
    ]);

    exit;
}


/* =========================
   GET VALUES
========================= */

$name = trim($input["name"] ?? "");

$email = trim($input["email"] ?? "");

$phone = trim($input["phone"] ?? "");

$birth_date = trim(
    $input["birth_date"] ?? ""
);

$gender = trim(
    $input["gender"] ?? ""
);

$address = trim(
    $input["address"] ?? ""
);

$language = trim(
    $input["language"] ?? ""
);

$timezone = trim(
    $input["timezone"] ?? ""
);

$about = trim(
    $input["about"] ?? ""
);


/* =========================
   VALIDATION
========================= */

if ($name === "" || $email === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Name and email are required."
    ]);

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid email address."
    ]);

    exit;
}


/* =========================
   CHECK EMAIL
========================= */

$check = $conn->prepare("
    SELECT id
    FROM users
    WHERE email = ?
    AND id != ?
    LIMIT 1
");

$check->bind_param(
    "si",
    $email,
    $user_id
);

$check->execute();

$check_result = $check->get_result();


if ($check_result->num_rows > 0) {

    $check->close();

    http_response_code(409);

    echo json_encode([
        "success" => false,
        "message" => "This email is already used."
    ]);

    exit;
}


$check->close();


/* =========================
   UPDATE USER
========================= */

$stmt = $conn->prepare("
    UPDATE users
    SET
        name = ?,
        email = ?,
        phone = ?,
        birth_date = ?,
        gender = ?,
        address = ?,
        language = ?,
        timezone = ?,
        about = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");


$stmt->bind_param(
    "sssssssssi",
    $name,
    $email,
    $phone,
    $birth_date,
    $gender,
    $address,
    $language,
    $timezone,
    $about,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update profile."
    ]);

    exit;
}


$stmt->close();


/* =========================
   UPDATE SESSION
========================= */

$_SESSION["user_name"] = $name;

$_SESSION["user_email"] = $email;


/* =========================
   SUCCESS
========================= */

echo json_encode([
    "success" => true,
    "message" => "Profile updated successfully."
]);

?>