<?php

session_start();

require_once "../php/config/database.php";

$errors = [];

$old = [
    'name'  => '',
    'email' => '',
    'phone' => '',
    'type'  => '',
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==========================================================
    // STEP 0: Make sure the form was actually submitted properly.
    // If any expected field key is completely missing from $_POST
    // (not just empty), stop and show a general error instead of
    // silently continuing with blanks.
    // ==========================================================
    $requiredKeys = ['name', 'email', 'phone', 'password', 'confirm_password', 'user_type'];
    $missingKey   = false;

    foreach ($requiredKeys as $key) {
        if (!isset($_POST[$key])) {
            $missingKey = true;
            break;
        }
    }

    if ($missingKey) {
        $errors['general'] = 'Invalid form submission: some required data was not sent. Please reload the page and try again.';
    } else {

        // ==========================================================
        // STEP 1: Sanitize raw input
        // trim -> stripslashes -> htmlspecialchars for text fields
        // ==========================================================
        $name = trim($_POST['name']);
        $name = stripslashes($name);
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $email = trim($_POST['email']);
        $email = stripslashes($email);

        $phone = trim($_POST['phone']);
        $phone = stripslashes($phone);

        $password        = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $userType        = trim($_POST['user_type']);
        $agree           = $_POST['agree'] ?? ''; // checkboxes are absent from $_POST when unchecked

        $old['name']  = $name;
        $old['email'] = $email;
        $old['phone'] = $phone;
        $old['type']  = $userType;

        // ==========================================================
        // STEP 2: Validate every field, one clear reason per error
        // ==========================================================

        // ---- Full Name ----
        // Required, 3–20 characters, English letters and spaces only
        if ($name === '') {
            $errors['name'] = 'Full name is required.';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'Full name must be at least 3 characters long.';
        } elseif (strlen($name) > 20) {
            $errors['name'] = 'Full name must not be longer than 20 characters.';
        } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
            $errors['name'] = 'Full name can only contain English letters (A-Z, a-z) and spaces.';
        }

        // ---- Email ----
        // Required, must be a valid email format
        if ($email === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address (e.g. name@example.com).';
        }

        // ---- Phone ----
        // Required, must be exactly 11 digits
        if ($phone === '') {
            $errors['phone'] = 'Phone number is required.';
        } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
            $errors['phone'] = 'Phone number must be exactly 11 digits (numbers only).';
        }

        // ---- Password ----
        // Required, min 8 chars, must include lowercase, uppercase, a number, and a special character
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (!preg_match($passwordPattern, $password)) {
            $errors['password'] = 'Password must be at least 8 characters and include an uppercase letter, a lowercase letter, a number, and a special character.';
        }

        // ---- Confirm Password ----
        // Required, must exactly match the password
        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Please confirm your password.';
        } elseif (!isset($errors['password']) && $confirmPassword !== $password) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        // ---- Account Type ----
        // Required, must be one of the allowed options
        if ($userType === '' || !in_array($userType, ['mother', 'father', 'sister'], true)) {
            $errors['user_type'] = 'Please select an account type.';
        }

        // ---- Terms Agreement ----
        // Required, checkbox must be checked
        if ($agree !== 'on') {
            $errors['agree'] = 'You must agree to the Terms of Use and Privacy Policy.';
        }

              // ==========================================================
        // STEP 3: If format is valid so far, check for duplicate email
        // ==========================================================

        if (empty($errors)) {

            $checkStmt = $conn->prepare(
                "SELECT id FROM users WHERE email = ? LIMIT 1"
            );

            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();

            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $errors['email'] = 'This email is already registered. Try logging in instead.';
            }

            $checkStmt->close();
        }

        // ==========================================================
        // STEP 4: Everything is valid -> insert into the database
        // ==========================================================

        if (empty($errors)) {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {

                $insertStmt = $conn->prepare(
    "INSERT INTO users (name, email, password, phone, role, user_type)
     VALUES (?, ?, ?, ?, 'user', ?)"
);

$insertStmt->bind_param(
    "sssss",
    $name,
    $email,
    $hashedPassword,
    $phone,
    $userType
);

                $insertStmt->execute();

                $newUserId = $conn->insert_id;

                // Login automatically after registration
                $_SESSION['user_id']    = $newUserId;
                $_SESSION['user_name']  = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['role']       = 'user';

                header('Location: dashboard.php');
                exit;

            } catch (mysqli_sql_exception $e) {

                $errors['general'] =
                    'Something went wrong while creating your account. Please try again later.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Baby Care - Register</title>
    <link rel="icon" type="image/png" href="../assets/icons/logo.png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* Error text under each field. min-height reserves the space
           whether or not an error is showing, so the button below
           never jumps up/down or gets pushed out of view. */
           /* ================= ERROR MESSAGES ================= */
        .error-msg {
    position: absolute;
    left: 0;
    top: 100%;
    margin-top: 2px;

    width: 100%;

    color: #e63946;
    font-size: 10px;
    line-height: 13px;

    white-space: normal;
    overflow: visible;

    pointer-events: none;
}
        .field{
            position: relative;
        }

        .input-box.has-error input,
        .input-box.has-error select {
            border-color: #e63946 !important;
        }

        .general-error {
            background: #ffe3e3;
            color: #c0392b;
            padding: 6px 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
            height: 32px;
            line-height: 20px;
            overflow: hidden;
        }

        .create-account {
            position: relative;
            width: 100%;
        }
</style>

</head>

<body>

<header>

    <img src="../assets/icons/logo.png" alt="Baby Care">

    <div class="links">
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <h1>|</h1>
        <a class="login" href="login.html">Login</a>
        <a class="register" href="register.php">Register</a>
    </div>

</header>

<section id="register">

    <div class="baby">
        <img src="../assets/images/Gemini_Generated_Image_x6sli6x6sli6x6sl 2.jpg" alt="Baby Care">
    </div>

    <div class="info">

        <h2>Register</h2>
        <p class="description">Please fill in your details to create an account.</p>

        <div class="general-error" style="<?= empty($errors['general']) ? 'visibility:hidden;' : '' ?>">
            <?= htmlspecialchars($errors['general'] ?? '') ?: '&nbsp;' ?>
        </div>

        <form action="register.php" method="POST" novalidate>

            <div class="row">

                <div class="field">
                    <label>Full Name</label>
                    <div class="input-box <?= isset($errors['name']) ? 'has-error' : '' ?>">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="name" placeholder="Enter your full name"
                            value="<?= htmlspecialchars($old['name']) ?>" required>
                    </div>
                    <span class="error-msg"><?= isset($errors['name']) ? htmlspecialchars($errors['name']) : '' ?></span>
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <div class="input-box <?= isset($errors['email']) ? 'has-error' : '' ?>">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email"
                            value="<?= htmlspecialchars($old['email']) ?>" required>
                    </div>
                    <span class="error-msg"><?= isset($errors['email']) ? htmlspecialchars($errors['email']) : '' ?></span>
                </div>

            </div>

            <div class="row">

                <div class="field">
                    <label>Phone Number</label>
                    <div class="input-box <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" name="phone" placeholder="11 digit phone number"
                            value="<?= htmlspecialchars($old['phone']) ?>" maxlength="11" required>
                    </div>
                    <span class="error-msg"><?= isset($errors['phone']) ? htmlspecialchars($errors['phone']) : '' ?></span>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-box <?= isset($errors['password']) ? 'has-error' : '' ?>">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Create a password" required>
                        <i class="fa-regular fa-eye eye"></i>
                    </div>
                    <span class="error-msg"><?= isset($errors['password']) ? htmlspecialchars($errors['password']) : '' ?></span>
                </div>

            </div>

            <div class="field">
                <label>Confirm Password</label>
                <div class="input-box <?= isset($errors['confirm_password']) ? 'has-error' : '' ?>">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                    <i class="fa-regular fa-eye eye"></i>
                </div>
                <span class="error-msg"><?= isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password']) : '' ?></span>
            </div>

            <div class="field">
                <label>I am a...</label>
                <div class="input-box <?= isset($errors['user_type']) ? 'has-error' : '' ?>">
                    <i class="fa-regular fa-user"></i>
                    <select name="user_type" required>
                        <option value="" disabled <?= $old['type'] === '' ? 'selected' : '' ?>>Select</option>
                        <option value="mother" <?= $old['type'] === 'mother' ? 'selected' : '' ?>>Mother</option>
                        <option value="father" <?= $old['type'] === 'father' ? 'selected' : '' ?>>Father</option>
                        <option value="sister" <?= $old['type'] === 'sister' ? 'selected' : '' ?>>Sister</option>
                    </select>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </div>
                <span class="error-msg"><?= isset($errors['user_type']) ? htmlspecialchars($errors['user_type']) : '' ?></span>
            </div>

            <div class="check">
                <input type="checkbox" name="agree">
                <span>I agree to the Terms of Use and Privacy Policy</span>
            </div>
            <span class="error-msg"><?= isset($errors['agree']) ? htmlspecialchars($errors['agree']) : '' ?></span>

            <button type="submit" class="create-account">
                <i class="fa-regular fa-user"></i>
                Create Account
            </button>

            <div class="or">
                <span></span>
              
                <span></span>
            </div>

            <p class="account">
                Already have an account?
                <a href="login.html">Login</a>
            </p>

        </form>

    </div>

</section>

<footer>

    <div>
        <i class="fa-solid fa-shield-halved"></i>
        <b>Secure & Private</b>
        <p>Your data is safe with us</p>
    </div>

    <div>
        <i class="fa-solid fa-baby"></i>
        <b>Made for Parents</b>
        <p>Designed for your baby's needs</p>
    </div>

    <div>
        <i class="fa-solid fa-chart-line"></i>
        <b>Track & Monitor</b>
        <p>Keep track of your baby's growth and health</p>
    </div>

    <div>
        <i class="fa-regular fa-heart"></i>
        <b>Care & Support</b>
        <p>Helpful tips and guidance at every step</p>
    </div>

</footer>

</body>

</html>
