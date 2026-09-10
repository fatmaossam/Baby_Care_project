<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../html/settings.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../html/settings.php");
    exit;
}

$current_password = $_POST["current_password"] ?? "";
$new_password = $_POST["new_password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

if (
    empty($current_password) ||
    empty($new_password) ||
    empty($confirm_password)
) {
    die("Please fill in all password fields.");
}

if ($new_password !== $confirm_password) {
    die("New password and confirmation do not match.");
}

if (strlen($new_password) < 8) {
    die("New password must be at least 8 characters.");
}

$stmt = $conn->prepare("
    SELECT password
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    die("User not found.");
}

$user = $result->fetch_assoc();

$stmt->close();

if (!password_verify($current_password, $user["password"])) {
    die("Current password is incorrect.");
}

$new_password_hash = password_hash(
    $new_password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare("
    UPDATE users
    SET
        password = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");

$stmt->bind_param(
    "si",
    $new_password_hash,
    $user_id
);

if ($stmt->execute()) {
    $stmt->close();

    header("Location: ../../html/settings.php?password=success");
    exit;
}

$stmt->close();

die("Failed to change password.");

?>