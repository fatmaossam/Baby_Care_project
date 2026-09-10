<?php

session_start();

require_once "../config/database.php";


// Protect page
if (!isset($_SESSION["user_id"])) {
    die("You must be logged in.");
}

$user_id = $_SESSION["user_id"];


// Get baby ID
$baby_id = intval($_GET["baby_id"] ?? 0);

if ($baby_id <= 0) {
    die("Invalid baby.");
}


// Make sure baby belongs to logged-in user
$stmt = $conn->prepare("
    SELECT id, name, birth_date
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

$baby = $result->fetch_assoc();

$stmt->close();


// Baby birth date
$birth_date = new DateTime($baby["birth_date"]);


// Vaccination schedule
$schedule = [

    // At birth
    [
        "vaccine_name" => "BCG",
        "months" => 0
    ],

    [
        "vaccine_name" => "Hepatitis B",
        "months" => 0
    ],

    [
        "vaccine_name" => "Oral Polio (OPV)",
        "months" => 0
    ],


    // 2 months
    [
        "vaccine_name" => "Pentavalent",
        "months" => 2
    ],

    [
        "vaccine_name" => "Inactivated Polio (IPV)",
        "months" => 2
    ],


    // 4 months
    [
        "vaccine_name" => "Pentavalent",
        "months" => 4
    ],

    [
        "vaccine_name" => "Inactivated Polio (IPV)",
        "months" => 4
    ],


    // 6 months
    [
        "vaccine_name" => "Pentavalent",
        "months" => 6
    ],

    [
        "vaccine_name" => "Inactivated Polio (IPV)",
        "months" => 6
    ],


    // 9 months
    [
        "vaccine_name" => "Oral Polio Booster",
        "months" => 9
    ],


    // 12 months
    [
        "vaccine_name" => "Oral Polio Booster",
        "months" => 12
    ],

    [
        "vaccine_name" => "MMR",
        "months" => 12
    ],


    // 18 months
    [
        "vaccine_name" => "Oral Polio Booster",
        "months" => 18
    ],

    [
        "vaccine_name" => "MMR",
        "months" => 18
    ],

    [
        "vaccine_name" => "DPT Booster",
        "months" => 18
    ]

];


// Start transaction
$conn->begin_transaction();

try {

    foreach ($schedule as $item) {

        // Get vaccine ID
        $stmt = $conn->prepare("
            SELECT id
            FROM vaccines
            WHERE name = ?
        ");

        $stmt->bind_param(
            "s",
            $item["vaccine_name"]
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            throw new Exception(
                "Vaccine not found: " . $item["vaccine_name"]
            );
        }

        $vaccine = $result->fetch_assoc();

        $vaccine_id = $vaccine["id"];

        $stmt->close();


        // Calculate due date
        $due_date_object = clone $birth_date;

        if ($item["months"] > 0) {

            $due_date_object->modify(
                "+" . $item["months"] . " months"
            );
        }

        $due_date = $due_date_object->format("Y-m-d");


        // Check if this vaccination already exists
        $stmt = $conn->prepare("
            SELECT id
            FROM baby_vaccinations
            WHERE baby_id = ?
            AND vaccine_id = ?
            AND due_date = ?
        ");

        $stmt->bind_param(
            "iis",
            $baby_id,
            $vaccine_id,
            $due_date
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $exists = $result->num_rows > 0;

        $stmt->close();


        // Insert only if it does not already exist
        if (!$exists) {

            $stmt = $conn->prepare("
                INSERT INTO baby_vaccinations
                (
                    baby_id,
                    vaccine_id,
                    due_date
                )
                VALUES (?, ?, ?)
            ");

            $stmt->bind_param(
                "iis",
                $baby_id,
                $vaccine_id,
                $due_date
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    "Failed to save vaccination schedule."
                );
            }

            $stmt->close();
        }
    }


    // Everything succeeded
    $conn->commit();

    echo "Vaccination schedule created successfully for "
        . htmlspecialchars($baby["name"])
        . ".";

} catch (Exception $e) {

    // Undo everything if something fails
    $conn->rollback();

    die(
        "Failed to create vaccination schedule: "
        . htmlspecialchars($e->getMessage())
    );
}

?>