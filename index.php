<?php

session_start();
require_once "csrf_token.php";

$con = mysqli_connect("localhost", "root", "1234", "users", 3306);

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$message = "";
$message_type = "";

if (isset($_POST['register'])) {

    // CSRF Protection
    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        die("Invalid CSRF Token ❌");
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name == "") {

        $message = "Please enter your name ❌";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email ❌";
        $message_type = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must be at least 8 characters ❌";
        $message_type = "error";

    } else {

        $hashed_password =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );

        $stmt = mysqli_prepare(
            $con,
            "INSERT INTO mydata(name, email, password)
             VALUES(?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $name,
            $email,
            $hashed_password
        );

        $execute =
            mysqli_stmt_execute($stmt);

        if ($execute) {

            $message =
                "Registration Successful! ✅";

            $message_type = "success";

        } else {

            if (mysqli_errno($con) == 1062) {

                $message =
                    "Email already registered ❌";

            } else {

                $message =
                    "Registration Failed ❌";
            }

            $message_type = "error";
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

    <title>Create Account | My Website</title>


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

        }


        .form-group {

            margin-bottom: 18px;

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


        .register-button {

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


        .register-button:hover {

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


        .success {

            background: #dcfce7;

            color: #166534;

        }


        .error {

            background: #fee2e2;

            color: #b91c1c;

        }


        .login-link {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #2563eb;

            text-decoration: none;

            font-size: 14px;

        }


        .login-link:hover {

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
            👤
        </div>


        <h1>Create Account</h1>

        <p class="subtitle">
            Register for your account
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


            <!-- Name -->

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your name"
                    maxlength="50"
                    required
                    autocomplete="name"
                >

            </div>


            <!-- Email -->

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    maxlength="50"
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
                        placeholder="Minimum 8 characters"
                        minlength="8"
                        required
                        autocomplete="new-password"
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


            <!-- Register -->

            <button
                type="submit"
                name="register"
                class="register-button"
            >
                Create Account
            </button>


            <!-- Login -->

            <a
                href="login.php"
                class="login-link"
            >
                Already have an account? Login
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
