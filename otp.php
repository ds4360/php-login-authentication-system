<?php

session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";

if (isset($_POST['verify'])) {

    $user_otp = $_POST['otp'];

    if ($user_otp == $_SESSION['otp']) {

        $_SESSION['otp_verified'] = true;

        header("Location: reset_password.php");
        exit();

    } else {

        $message = "Wrong OTP ❌";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Verify OTP</title>

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
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
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
            color: red;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>🔐 Verify OTP</h2>

    <p style="text-align:center;">
    Your OTP is:
    <strong><?php echo $_SESSION['otp']; ?></strong>
</p>

    <?php

    if ($message != "") {
        echo "<div class='message'>$message</div>";
    }

    ?>

    <form method="POST">

        <input
            type="text"
            name="otp"
            placeholder="Enter OTP"
            maxlength="6"
            required
        >

        <button type="submit" name="verify">
            Verify OTP
        </button>

    </form>

</div>

</body>

</html>