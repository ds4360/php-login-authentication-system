<?php

session_start();
require_once "csrf_token.php";

$con = mysqli_connect("localhost", "root", "1234", "users", 3306);

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (!isset($_SESSION['lock_time'])) {
    $_SESSION['lock_time'] = 0;
}

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}

$message = "";
$message_type = "";

if (isset($_POST['login'])) {

    // CSRF Protection
    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        die("Invalid CSRF Token ❌");
    }

    // Account Lock Check
    if ($_SESSION['lock_time'] > time()) {

        $remaining = $_SESSION['lock_time'] - time();

        $message =
            "Too many failed attempts. Try again after "
            . $remaining
            . " seconds.";

        $message_type = "error";

    } else {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = mysqli_prepare(
            $con,
            "SELECT name, email, password
             FROM mydata
             WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {

            die("Query Error: " . mysqli_error($con));

        }


        if (mysqli_num_rows($result) > 0) {

            $user = mysqli_fetch_assoc($result);


            // Password Check
            if (password_verify($password, $user['password'])) {

                // Successful Login
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lock_time'] = 0;

                session_regenerate_id(true);

                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                header("Location: dashboard.php");
                exit();

            } else {

                // Wrong Password
                $_SESSION['login_attempts']++;

                if ($_SESSION['login_attempts'] >= 5) {

                    $_SESSION['lock_time'] = time() + 600;

                    $message =
                        "Too many failed attempts ❌ "
                        . "Please try again after 10 minutes.";

                    $message_type = "error";

                } else {

                    $remaining_attempts =
                        5 - $_SESSION['login_attempts'];

                    $message =
                        "Wrong email or password ❌ "
                        . "Attempts remaining: "
                        . $remaining_attempts;

                    $message_type = "error";
                }
            }

        } else {

            // Wrong Email
            $_SESSION['login_attempts']++;

            if ($_SESSION['login_attempts'] >= 5) {

                $_SESSION['lock_time'] = time() + 600;

                $message =
                    "Too many failed attempts ❌ "
                    . "Please try again after 10 minutes.";

                $message_type = "error";

            } else {

                $remaining_attempts =
                    5 - $_SESSION['login_attempts'];

                $message =
                    "Wrong email or password ❌ "
                    . "Attempts remaining: "
                    . $remaining_attempts;

                $message_type = "error";
            }
        }

        mysqli_stmt_close($stmt);
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

    <title>Login | My Website</title>


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


        .login-container {

            width: 100%;

            max-width: 420px;

        }


        .login-card {

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


        .input-box {

            position: relative;

        }


        input {

            width: 100%;

            padding: 13px 45px 13px 14px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            font-size: 15px;

            outline: none;

            transition: 0.3s;

        }


        input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.12);

        }


        .show-password {

            position: absolute;

            right: 10px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: none;

            color: #2563eb;

            cursor: pointer;

            font-weight: bold;

        }


        .login-button {

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


        .login-button:hover {

            background: #1d4ed8;

            transform: translateY(-1px);

        }


        .forgot {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #2563eb;

            text-decoration: none;

            font-size: 14px;

        }


        .forgot:hover {

            text-decoration: underline;

        }


        .message {

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 8px;

            text-align: center;

            font-size: 14px;

            font-weight: bold;

            background: #fee2e2;

            color: #b91c1c;

        }


        .footer {

            text-align: center;

            color: #9ca3af;

            font-size: 12px;

            margin-top: 25px;

        }


        @media (max-width: 480px) {

            .login-card {

                padding: 28px 20px;

            }

            h1 {

                font-size: 24px;

            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <div class="login-card">


        <div class="logo">
            🔐
        </div>


        <h1>Welcome Back</h1>

        <p class="subtitle">
            Login to your account
        </p>


        <?php

        if ($message != "") {

            echo
                "<div class='message'>"
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


            <!-- Email -->

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    autocomplete="email"
                >

            </div>


            <!-- Password -->

            <div class="form-group">

                <label>
                    Password
                </label>


                <div class="input-box">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >


                    <button
                        type="button"
                        class="show-password"
                        onclick="showPassword()"
                        id="showButton"
                    >
                        Show
                    </button>

                </div>

            </div>


            <!-- Login -->

            <button
                type="submit"
                name="login"
                class="login-button"
            >
                Login
            </button>


            <!-- Forgot Password -->

            <a
                href="forgot_password.php"
                class="forgot"
            >
                Forgot Password?
            </a>


        </form>


        <div class="footer">

            My Website © 2026

        </div>


    </div>


</div>


<script>

function showPassword() {

    const password =
        document.getElementById("password");

    const button =
        document.getElementById("showButton");


    if (password.type === "password") {

        password.type = "text";

        button.textContent = "Hide";

    } else {

        password.type = "password";

        button.textContent = "Show";

    }

}

</script>


</body>

</html>
```
