<?php

require_once "session_check.php";

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Profile | My Website</title>


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
                0 4px 15px rgba(0,0,0,0.15);

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

            transform: translateY(-1px);

        }


        /* Container */

        .container {

            width: 90%;

            max-width: 520px;

            margin: 50px auto;

        }


        /* Profile Card */

        .profile-card {

            background: white;

            padding: 40px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px rgba(0,0,0,0.10);

        }


        /* Profile Icon */

        .profile-icon {

            width: 90px;

            height: 90px;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: #2563eb;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 42px;

            box-shadow:
                0 8px 20px rgba(37,99,235,0.25);

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


        /* Information */

        .info {

            background: #f8fafc;

            border: 1px solid #e5e7eb;

            padding: 16px;

            border-radius: 10px;

            margin-bottom: 15px;

        }


        .info strong {

            display: block;

            margin-bottom: 7px;

            color: #6b7280;

            font-size: 13px;

        }


        .info span {

            font-size: 16px;

            font-weight: 600;

            color: #111827;

            word-break: break-word;

        }


        /* Status */

        .active {

            display: inline-block;

            background: #dcfce7;

            color: #166534 !important;

            padding: 5px 11px;

            border-radius: 20px;

            font-size: 13px !important;

        }


        /* Buttons */

        .buttons {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-top: 25px;

        }


        .buttons a {

            text-align: center;

            padding: 13px;

            border-radius: 9px;

            text-decoration: none;

            font-weight: bold;

            transition: 0.3s;

        }


        .edit {

            background: #2563eb;

            color: white;

        }


        .edit:hover {

            background: #1d4ed8;

            transform: translateY(-1px);

        }


        .back {

            background: #f1f5f9;

            color: #374151;

        }


        .back:hover {

            background: #e2e8f0;

            transform: translateY(-1px);

        }


        .password {

            display: block;

            margin-top: 12px;

            text-align: center;

            padding: 13px;

            border-radius: 9px;

            background: #eff6ff;

            color: #2563eb;

            text-decoration: none;

            font-weight: bold;

            transition: 0.3s;

        }


        .password:hover {

            background: #dbeafe;

        }


        /* Footer */

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


            .profile-card {

                padding: 28px 20px;

            }


            .buttons {

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



<!-- Profile -->

<div class="container">


    <div class="profile-card">


        <div class="profile-icon">

            👤

        </div>


        <h1>

            My Profile

        </h1>


        <p class="subtitle">

            Your account information

        </p>


        <!-- Name -->

        <div class="info">

            <strong>

                FULL NAME

            </strong>


            <span>

                <?php

                echo htmlspecialchars(
                    $_SESSION['name']
                );

                ?>

            </span>

        </div>


        <!-- Email -->

        <div class="info">

            <strong>

                EMAIL ADDRESS

            </strong>


            <span>

                <?php

                echo htmlspecialchars(
                    $_SESSION['email']
                );

                ?>

            </span>

        </div>


        <!-- Status -->

        <div class="info">

            <strong>

                ACCOUNT STATUS

            </strong>


            <span class="active">

                Active 🟢

            </span>

        </div>


        <!-- Buttons -->

        <div class="buttons">


            <a
                class="edit"
                href="edit_profile.php"
            >

                ✏️ Edit Profile

            </a>


            <a
                class="back"
                href="dashboard.php"
            >

                ← Dashboard

            </a>


        </div>


        <a
            class="password"
            href="change_password.php"
        >

            🔐 Change Password

        </a>


    </div>


    <div class="footer">

        My Website © 2026

    </div>


</div>


</body>

</html>
```
