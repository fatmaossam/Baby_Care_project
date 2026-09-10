<?php

session_start();

require_once "../config/database.php";


// ==========================================================
// PROTECT PAGE
// ==========================================================

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in.");
}

$user_id = (int) $_SESSION["user_id"];


// ==========================================================
// ONLY POST REQUESTS
// ==========================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}


// ==========================================================
// GET FORM DATA
// ==========================================================

$baby_id = (int) ($_POST["baby_id"] ?? 0);

// This is vaccines.id
$vaccine_id = (int) ($_POST["vaccination_id"] ?? 0);

$date_taken = $_POST["date_taken"] ?? "";
$notes = trim($_POST["notes"] ?? "");


// ==========================================================
// VALIDATE BABY
// ==========================================================

if ($baby_id <= 0) {
    die("Please select a baby.");
}


// ==========================================================
// VALIDATE VACCINE
// ==========================================================

if ($vaccine_id <= 0) {
    die("Please select a vaccination.");
}


// ==========================================================
// VALIDATE DATE
// ==========================================================

if (empty($date_taken)) {
    die("Please enter the vaccination date.");
}


// ==========================================================
// MAKE SURE BABY BELONGS TO LOGGED-IN USER
// ==========================================================

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
    die("Invalid baby.");
}

$stmt->close();


// ==========================================================
// MAKE SURE VACCINE EXISTS
// ==========================================================

$stmt = $conn->prepare("
    SELECT id
    FROM vaccines
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $vaccine_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    die("Invalid vaccination.");
}

$stmt->close();


// ==========================================================
// FIND PENDING VACCINATION FOR THIS BABY
// ==========================================================
// If the selected baby already has a scheduled dose,
// update that dose instead of creating another record.

$stmt = $conn->prepare("
    SELECT id
    FROM baby_vaccinations
    WHERE baby_id = ?
    AND vaccine_id = ?
    AND date_taken IS NULL
    ORDER BY ABS(DATEDIFF(due_date, ?)) ASC
    LIMIT 1
");

$stmt->bind_param(
    "iis",
    $baby_id,
    $vaccine_id,
    $date_taken
);

$stmt->execute();

$result = $stmt->get_result();


// ==========================================================
// EXISTING SCHEDULE → MARK AS COMPLETED
// ==========================================================

if ($result->num_rows === 1) {

    $vaccination = $result->fetch_assoc();

    $baby_vaccination_id = (int) $vaccination["id"];

    $stmt->close();


    $stmt = $conn->prepare("
        UPDATE baby_vaccinations
        SET
            date_taken = ?,
            notes = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
        AND baby_id = ?
    ");

    $stmt->bind_param(
        "ssii",
        $date_taken,
        $notes,
        $baby_vaccination_id,
        $baby_id
    );


    if ($stmt->execute()) {

        $stmt->close();

        header("Location: ../../html/vaccination.php?success=1");
        exit;

    } else {

        $stmt->close();

        die("Failed to record vaccination.");

    }
}


// ==========================================================
// NO PENDING SCHEDULE
// ==========================================================

$stmt->close();


// Check if this baby already has this vaccine recorded.
// This prevents creating unnecessary duplicate records.

$stmt = $conn->prepare("
    SELECT id
    FROM baby_vaccinations
    WHERE baby_id = ?
    AND vaccine_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "ii",
    $baby_id,
    $vaccine_id
);

$stmt->execute();

$result = $stmt->get_result();


// ==========================================================
// ALREADY RECORDED
// ==========================================================

if ($result->num_rows > 0) {

    $stmt->close();

    die("This vaccination is already recorded for this baby.");

}

$stmt->close();


// ==========================================================
// CREATE NEW MANUAL VACCINATION RECORD
// ==========================================================
// If there is no schedule for this baby yet,
// create a completed record using the date entered.

$stmt = $conn->prepare("
    INSERT INTO baby_vaccinations
    (
        baby_id,
        vaccine_id,
        due_date,
        date_taken,
        notes,
        created_at,
        updated_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        CURRENT_TIMESTAMP,
        CURRENT_TIMESTAMP
    )
");

$stmt->bind_param(
    "iisss",
    $baby_id,
    $vaccine_id,
    $date_taken,
    $date_taken,
    $notes
);


if ($stmt->execute()) {

    $stmt->close();

    header("Location: ../../html/vaccination.php?success=1");
    exit;

} else {

    $stmt->close();

    die("Failed to record vaccination.");

}

?>