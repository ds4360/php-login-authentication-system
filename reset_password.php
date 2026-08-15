<?php

session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot_password.php");
    exit();
}

$con = mysqli_connect("localhost", "root", "1234", "users", 3306);

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$message = "";

if (isset($_POST['reset'])) {

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {

        $message = "Passwords do not match ❌";

    } else {

        $email = $_SESSION['reset_email'];

        $hashed_password = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $query = "UPDATE mydata
                  SET password='$hashed_password'
                  WHERE email='$email'";

        $result = mysqli_query($con, $query);

        if ($result) {

            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['otp_verified']);

            $message = "Password Reset Successfully! ✅";

        } else {

            $message = "Error: " . mysqli_error($con);

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Reset Password</title>

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
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: green;
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

    <h2>🔐 Reset Password</h2>

    <?php

    if ($message != "") {
        echo "<div class='message'>$message</div>";
    }

    ?>

    <form method="POST">

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

    <a class="back" href="login.php">
        ← Back to Login
    </a>

</div>

</body>

</html>