<?php

require_once "session_check.php";
require_once "csrf_token.php";

$con = mysqli_connect(
    "localhost",
    "root",
    "1234",
    "users",
    3306
);

if (!$con) {
    die(
        "Database Connection Failed: "
        . mysqli_connect_error()
    );
}

$message = "";
$message_type = "";


if (isset($_POST['change'])) {

    // CSRF Protection

    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {

        die("Invalid CSRF Token ❌");

    }


    $current_password =
        $_POST['current_password'];

    $new_password =
        $_POST['new_password'];

    $confirm_password =
        $_POST['confirm_password'];


    $email =
        $_SESSION['email'];


    // Get current hashed password

    $stmt = mysqli_prepare(
        $con,
        "SELECT password
         FROM mydata
         WHERE email = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );


    mysqli_stmt_execute($stmt);


    $result =
        mysqli_stmt_get_result($stmt);


    if (
        !$result ||
        mysqli_num_rows($result) == 0
    ) {

        $message =
            "User not found ❌";

        $message_type =
            "error";

    } else {

        $user =
            mysqli_fetch_assoc($result);


        // Verify Current Password

        if (
            !password_verify(
                $current_password,
                $user['password']
            )
        ) {

            $message =
                "Current Password is Wrong ❌";

            $message_type =
                "error";


        } elseif (
            strlen($new_password) < 8
        ) {

            $message =
                "New Password must be at least 8 characters ❌";

            $message_type =
                "error";


        } elseif (
            $new_password != $confirm_password
        ) {

            $message =
                "New Password and Confirm Password do not match ❌";

            $message_type =
                "error";


        } elseif (
            password_verify(
                $new_password,
                $user['password']
            )
        ) {

            $message =
                "New Password must be different from Current Password ❌";

            $message_type =
                "error";


        } else {


            // Hash New Password

            $hashed_password =
                password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );


            // Update Password

            $update =
                mysqli_prepare(
                    $con,
                    "UPDATE mydata
                     SET password = ?
                     WHERE email = ?"
                );


            mysqli_stmt_bind_param(
                $update,
                "ss",
                $hashed_password,
                $email
            );


            if (
                mysqli_stmt_execute($update)
            ) {

                $message =
                    "Password Changed Successfully! ✅";

                $message_type =
                    "success";


            } else {

                $message =
                    "Password Change Failed ❌";

                $message_type =
                    "error";

            }


            mysqli_stmt_close($update);

        }

    }


    mysqli_stmt_close($stmt);

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

    <title>Change Password | My Website</title>


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
                    #f1f5f9,
                    #dbeafe
                );

            color: #111827;

        }


        /* Navbar */

        .navbar {

            height: 72px;

            background: #0f172a;

            color: white;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 45px;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.15);

        }


        .logo {

            font-size: 22px;

            font-weight: bold;

        }


        .dashboard-btn {

            background: #2563eb;

            color: white;

            text-decoration: none;

            padding: 10px 17px;

            border-radius: 8px;

            font-weight: bold;

            transition: 0.3s;

        }


        .dashboard-btn:hover {

            background: #1d4ed8;

        }


        /* Container */

        .container {

            width: 90%;

            max-width: 520px;

            margin: 50px auto;

        }


        /* Card */

        .password-card {

            background: white;

            padding: 40px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px
                rgba(0,0,0,0.10);

        }


        /* Icon */

        .password-icon {

            width: 85px;

            height: 85px;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: #2563eb;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 38px;

            color: white;

            box-shadow:
                0 8px 20px
                rgba(37,99,235,0.25);

        }


        h1 {

            text-align: center;

            font-size: 28px;

            margin-bottom: 8px;

        }


        .subtitle {

            text-align: center;

            color: #6b7280;

            font-size: 14px;

            margin-bottom: 30px;

        }


        /* Message */

        .message {

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 8px;

            text-align: center;

            font-size: 14px;

            font-weight: bold;

        }


        .success {

            background: #dcfce7;

            color: #166534;

        }


        .error {

            background: #fee2e2;

            color: #b91c1c;

        }


        /* Form */

        .form-group {

            margin-bottom: 20px;

        }


        label {

            display: block;

            margin-bottom: 8px;

            color: #374151;

            font-size: 14px;

            font-weight: bold;

        }


        input {

            width: 100%;

            padding: 13px 14px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            font-size: 15px;

            outline: none;

            transition: 0.3s;

        }


        input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37,99,235,0.12);

        }


        /* Button */

        .change-button {

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


        .change-button:hover {

            background: #1d4ed8;

            transform: translateY(-1px);

        }


        /* Back Buttons */

        .back-buttons {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-top: 15px;

        }


        .back-button {

            text-align: center;

            padding: 12px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;

            background: #f1f5f9;

            color: #374151;

            transition: 0.3s;

        }


        .back-button:hover {

            background: #e2e8f0;

        }


        .footer {

            text-align: center;

            color: #9ca3af;

            font-size: 12px;

            margin-top: 25px;

        }


        /* Mobile */

        @media (max-width: 600px) {

            .navbar {

                padding: 0 20px;

            }


            .logo {

                font-size: 18px;

            }


            .dashboard-btn {

                padding: 8px 12px;

                font-size: 13px;

            }


            .container {

                width: 92%;

                margin: 30px auto;

            }


            .password-card {

                padding: 28px 20px;

            }


            .back-buttons {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>


<body>


<!-- Navbar -->

<div class="navbar">


    <div class="logo">

        🌐 My Website

    </div>


    <a
        class="dashboard-btn"
        href="dashboard.php"
    >

        🏠 Dashboard

    </a>


</div>



<!-- Change Password -->

<div class="container">


    <div class="password-card">


        <div class="password-icon">

            🔐

        </div>


        <h1>

            Change Password

        </h1>


        <p class="subtitle">

            Keep your account secure with a strong password

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


            <!-- CSRF -->

            <input
                type="hidden"
                name="csrf_token"
                value="<?php
                    echo htmlspecialchars(
                        csrf_token()
                    );
                ?>"
            >


            <!-- Current Password -->

            <div class="form-group">

                <label>

                    Current Password

                </label>


                <input
                    type="password"
                    name="current_password"
                    placeholder="Enter current password"
                    required
                >

            </div>


            <!-- New Password -->

            <div class="form-group">

                <label>

                    New Password

                </label>


                <input
                    type="password"
                    name="new_password"
                    placeholder="Enter new password"
                    minlength="8"
                    required
                >

            </div>


            <!-- Confirm Password -->

            <div class="form-group">

                <label>

                    Confirm New Password

                </label>


                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm new password"
                    minlength="8"
                    required
                >

            </div>


            <button
                type="submit"
                name="change"
                class="change-button"
            >

                🔐 Change Password

            </button>


        </form>


        <div class="back-buttons">


            <a
                class="back-button"
                href="profile.php"
            >

                ← Profile

            </a>


            <a
                class="back-button"
                href="dashboard.php"
            >

                🏠 Dashboard

            </a>


        </div>


    </div>


    <div class="footer">

        My Website © 2026

    </div>


</div>


</body>

</html>
