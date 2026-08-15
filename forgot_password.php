<?php

session_start();

require_once "csrf_token.php";

$con = mysqli_connect("localhost", "root", "1234", "users", 3306);

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$message = "";
$show_reset = false;


/*
|--------------------------------------------------------------------------
| Show Reset Form
|--------------------------------------------------------------------------
*/

if (
    isset($_SESSION['otp_verified']) &&
    $_SESSION['otp_verified'] === true &&
    isset($_GET['reset']) &&
    $_GET['reset'] == 1
) {
    $show_reset = true;
}


/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/

if (isset($_POST['reset'])) {

    // CSRF Check
    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        die("Invalid CSRF Token ❌");
    }

    // OTP Verification Check
    if (
        !isset($_SESSION['otp_verified']) ||
        $_SESSION['otp_verified'] !== true
    ) {
        die("OTP verification required.");
    }

    $email = $_SESSION['reset_email'];

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    // Password Match
    if ($new_password !== $confirm_password) {

        $message = "Passwords do not match ❌";
        $show_reset = true;

    } else {

        // Hash Password
        $hashed_password = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );


        // Update Password
        $stmt = mysqli_prepare(
            $con,
            "UPDATE mydata SET password = ? WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $hashed_password,
            $email
        );

        $result = mysqli_stmt_execute($stmt);


        if ($result) {

    unset($_SESSION['otp_verified']);
    unset($_SESSION['reset_email']);
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    header("Location: login.php?reset=success");
    exit();
        } else {

            $message = "Error: " . mysqli_error($con);
            $show_reset = true;

        }

        mysqli_stmt_close($stmt);
    }
}


/*
|--------------------------------------------------------------------------
| Check Email
|--------------------------------------------------------------------------
*/

if (isset($_POST['check'])) {

    // CSRF Check
    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        die("Invalid CSRF Token ❌");
    }

    $email = trim($_POST['email']);


    // Check Email
    $stmt = mysqli_prepare(
        $con,
        "SELECT email FROM mydata WHERE email = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    if (mysqli_num_rows($result) > 0) {

        // Store Email
        $_SESSION['reset_email'] = $email;

        // Generate OTP
        $_SESSION['otp'] = random_int(100000, 999999);

        // OTP Time
        $_SESSION['otp_time'] = time();

        header("Location: send_otp.php");
        exit();

    } else {

        $message = "Email Not Found ❌";

    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password</title>

    <style>

        body {
            font-family: Arial;
            background: #f4f6f9;
        }

        .container {
            width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ccc;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Forgot Password 🔐</h2>


    <?php

    if ($message != "") {

        echo "<div class='message'>"
            . htmlspecialchars($message)
            . "</div>";

    }

    ?>


    <!-- Check Email Form -->

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?php echo htmlspecialchars(csrf_token()); ?>"
        >

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <button type="submit" name="check">
            Check Email
        </button>

    </form>


    <a class="back" href="login.php">
        ← Back to Login
    </a>


    <?php

    if ($show_reset) {

    ?>


    <!-- Reset Password Form -->

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?php echo htmlspecialchars(csrf_token()); ?>"
        >

        <input
            type="password"
            name="new_password"
            placeholder="Enter New Password"
            required
        >

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm New Password"
            required
        >

        <button type="submit" name="reset">
            Reset Password
        </button>

    </form>


    <?php

    }

    ?>

</div>

</body>

</html>
