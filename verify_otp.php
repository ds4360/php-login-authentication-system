
<?php

session_start();

require_once "csrf_token.php";

$message = "";
$message_type = "error";


/*
|--------------------------------------------------------------------------
| Check OTP Session
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['otp']) ||
    !isset($_SESSION['otp_time']) ||
    !isset($_SESSION['reset_email'])
) {
    die("Invalid OTP request.");
}


/*
|--------------------------------------------------------------------------
| OTP Attempt Counter
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['otp_attempts'])) {
    $_SESSION['otp_attempts'] = 0;
}


/*
|--------------------------------------------------------------------------
| Verify OTP
|--------------------------------------------------------------------------
*/

if (isset($_POST['verify'])) {

    // CSRF Protection
    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        die("Invalid CSRF Token ❌");
    }


    // Maximum 5 attempts
    if ($_SESSION['otp_attempts'] >= 5) {

        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        unset($_SESSION['otp_attempts']);

        die("Too many wrong OTP attempts ❌ Please request a new OTP.");

    }


    $user_otp = trim($_POST['otp']);


    /*
    |--------------------------------------------------------------------------
    | OTP Format Check
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/^[0-9]{6}$/', $user_otp)) {

        $message = "Please enter a valid 6 digit OTP ❌";
        $message_type = "error";

    }


    /*
    |--------------------------------------------------------------------------
    | OTP Expiry - 5 Minutes
    |--------------------------------------------------------------------------
    */

    elseif (time() - $_SESSION['otp_time'] > 300) {

        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        unset($_SESSION['otp_attempts']);

        $message = "OTP Expired ❌ Please request a new OTP.";
        $message_type = "error";

    }


    /*
    |--------------------------------------------------------------------------
    | OTP Verification
    |--------------------------------------------------------------------------
    */

    elseif (hash_equals((string) $_SESSION['otp'], $user_otp)) {

        $_SESSION['otp_verified'] = true;

        // Clear OTP data
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        unset($_SESSION['otp_attempts']);

        header("Location: forgot_password.php?reset=1");
        exit();

    }


    /*
    |--------------------------------------------------------------------------
    | Wrong OTP
    |--------------------------------------------------------------------------
    */

    else {

        $_SESSION['otp_attempts']++;

        $remaining_attempts =
            5 - $_SESSION['otp_attempts'];

        if ($remaining_attempts <= 0) {

            unset($_SESSION['otp']);
            unset($_SESSION['otp_time']);
            unset($_SESSION['otp_attempts']);

            $message =
                "Too many wrong OTP attempts ❌ Please request a new OTP.";

        } else {

            $message =
                "Wrong OTP ❌ Attempts remaining: "
                . $remaining_attempts;
        }

        $message_type = "error";
    }
}

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verify OTP | My Website</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            min-height: 100vh;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #1d4ed8
                );

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 20px;

        }


        .container {

            width: 100%;

            max-width: 420px;

        }


        .card {

            background: white;

            padding: 40px;

            border-radius: 18px;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.25);

        }


        .logo {

            width: 65px;

            height: 65px;

            margin: 0 auto 20px;

            background: #2563eb;

            color: white;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

        }


        h1 {

            text-align: center;

            color: #111827;

            margin-bottom: 8px;

            font-size: 28px;

        }


        .subtitle {

            text-align: center;

            color: #6b7280;

            margin-bottom: 30px;

            font-size: 14px;

            line-height: 1.5;

        }


        .form-group {

            margin-bottom: 20px;

        }


        label {

            display: block;

            margin-bottom: 8px;

            color: #374151;

            font-weight: bold;

            font-size: 14px;

        }


        input {

            width: 100%;

            padding: 14px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            font-size: 18px;

            outline: none;

            text-align: center;

            letter-spacing: 6px;

            transition: 0.3s;

        }


        input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.12);

        }


        .verify-button {

            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 8px;

            background: #2563eb;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;

        }


        .verify-button:hover {

            background: #1d4ed8;

            transform: translateY(-1px);

        }


        .message {

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 8px;

            text-align: center;

            font-size: 14px;

            font-weight: bold;

        }


        .error {

            background: #fee2e2;

            color: #b91c1c;

        }


        .info {

            margin-top: 20px;

            text-align: center;

            color: #6b7280;

            font-size: 13px;

            line-height: 1.5;

        }


        .back {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #2563eb;

            text-decoration: none;

            font-size: 14px;

        }


        .back:hover {

            text-decoration: underline;

        }


        .footer {

            text-align: center;

            color: #9ca3af;

            font-size: 12px;

            margin-top: 25px;

        }


        @media (max-width: 480px) {

            .card {

                padding: 28px 20px;

            }

            h1 {

                font-size: 24px;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <div class="card">


        <div class="logo">
            🔐
        </div>


        <h1>
            Verify OTP
        </h1>


        <p class="subtitle">
            Enter the 6-digit OTP sent to your email.
            <br>
            OTP is valid for 5 minutes.
        </p>


        <?php

        if ($message != "") {

            echo
                "<div class='message "
                . htmlspecialchars($message_type)
                . "'>"
                . htmlspecialchars($message)
                . "</div>";

        }

        ?>


        <form method="POST">


            <!-- CSRF Token -->

            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo htmlspecialchars(csrf_token()); ?>"
            >


            <!-- OTP -->

            <div class="form-group">

                <label>
                    Enter OTP
                </label>

                <input
                    type="text"
                    name="otp"
                    placeholder="000000"
                    maxlength="6"
                    minlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    required
                >

            </div>


            <!-- Verify -->

            <button
                type="submit"
                name="verify"
                class="verify-button"
            >
                Verify OTP
            </button>


        </form>


        <p class="info">
            Didn't receive the OTP?
            Check your email spam/junk folder.
        </p>


        <a
            href="forgot_password.php"
            class="back"
        >
            ← Back to Forgot Password
        </a>


        <div class="footer">

            My Website © 2026

        </div>


    </div>


</div>


</body>

</html>

