<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check empty fields
    if (empty($email) || empty($password)) {
        die("Please enter your email and password.");
    }

    // Get user by email
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $user["password"])) {

            // Create session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            // Go to Dashboard
            header("Location: ../../html/dashboard.php");
            exit;

        } else {

            die("Invalid email or password.");

        }

    } else {

        die("Invalid email or password.");

    }

    $stmt->close();
}

?>