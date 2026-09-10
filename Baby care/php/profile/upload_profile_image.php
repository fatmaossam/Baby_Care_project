<?php

session_start();

header("Content-Type: application/json");

require_once "../config/database.php";


/* Check Login */
if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in."
    ]);

    exit;
}


$user_id = $_SESSION["user_id"];


/* Check File */
if (!isset($_FILES["profile_image"])) {

    echo json_encode([
        "success" => false,
        "message" => "No image was uploaded."
    ]);

    exit;
}


$file = $_FILES["profile_image"];


/* Check Upload Error */
if ($file["error"] !== UPLOAD_ERR_OK) {

    echo json_encode([
        "success" => false,
        "message" => "Image upload failed."
    ]);

    exit;
}


/* Maximum 5 MB */
if ($file["size"] > 5 * 1024 * 1024) {

    echo json_encode([
        "success" => false,
        "message" => "Image must be less than 5 MB."
    ]);

    exit;
}


/* Allowed Image Types */
$allowedTypes = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp"
];


$finfo = new finfo(FILEINFO_MIME_TYPE);

$mimeType = $finfo->file($file["tmp_name"]);


if (!isset($allowedTypes[$mimeType])) {

    echo json_encode([
        "success" => false,
        "message" => "Only JPG, PNG and WEBP images are allowed."
    ]);

    exit;
}


$extension = $allowedTypes[$mimeType];


/* Upload Folder */
$uploadDir = "../../assets/uploads/profile/";


if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0755, true);

}


/* Get Old Image */
$stmt = $conn->prepare("
    SELECT profile_image
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

$stmt->close();


$oldImage = $user["profile_image"] ?? "";


/* New File Name */
$fileName =
    "profile_" .
    $user_id .
    "_" .
    bin2hex(random_bytes(8)) .
    "." .
    $extension;


$targetPath = $uploadDir . $fileName;


/* Move Uploaded File */
if (!move_uploaded_file(
    $file["tmp_name"],
    $targetPath
)) {

    echo json_encode([
        "success" => false,
        "message" => "Could not save image."
    ]);

    exit;
}


/* Path saved in Database */
$databasePath =
    "assets/uploads/profile/" . $fileName;


/* Update Database */
$stmt = $conn->prepare("
    UPDATE users
    SET
        profile_image = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");

$stmt->bind_param(
    "si",
    $databasePath,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    echo json_encode([
        "success" => false,
        "message" => "Failed to save image information."
    ]);

    exit;
}


$stmt->close();


/* Delete Old Image */
if (!empty($oldImage)) {

    $oldImagePath =
        "../../" . ltrim($oldImage, "/");

    if (
        file_exists($oldImagePath) &&
        is_file($oldImagePath)
    ) {

        unlink($oldImagePath);

    }

}


/* Success */
echo json_encode([
    "success" => true,
    "message" => "Profile image updated successfully.",
    "image" => "../" . $databasePath
]);

exit;

?>