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

    <title>Dashboard | My Website</title>


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

            justify-content: space-between;

            align-items: center;

            padding: 0 45px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.15);

        }


        .logo {

            font-size: 22px;

            font-weight: bold;

        }


        .logout {

            background: #ef4444;

            color: white;

            text-decoration: none;

            padding: 10px 18px;

            border-radius: 8px;

            font-weight: bold;

            transition: 0.3s;

        }


        .logout:hover {

            background: #dc2626;

            transform: translateY(-1px);

        }


        /* Main */

        .container {

            width: 90%;

            max-width: 1100px;

            margin: 45px auto;

        }


        /* Welcome */

        .welcome {

            background: white;

            padding: 30px;

            border-radius: 16px;

            margin-bottom: 30px;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.08);

        }


        .welcome h1 {

            font-size: 30px;

            margin-bottom: 10px;

        }


        .welcome p {

            color: #6b7280;

            font-size: 15px;

        }


        .username {

            color: #2563eb;

        }


        /* Cards */

        .cards {

            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(280px, 1fr)
                );

            gap: 25px;

        }


        .card {

            background: white;

            padding: 30px;

            border-radius: 16px;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.08);

            transition: 0.3s;

        }


        .card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 14px 30px rgba(0,0,0,0.12);

        }


        .card-icon {

            width: 55px;

            height: 55px;

            border-radius: 12px;

            background: #eff6ff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 26px;

            margin-bottom: 18px;

        }


        .card h3 {

            font-size: 20px;

            margin-bottom: 18px;

        }


        .info {

            margin-bottom: 15px;

            color: #6b7280;

            line-height: 1.5;

        }


        .info strong {

            color: #374151;

        }


        /* Status */

        .status {

            display: inline-block;

            background: #dcfce7;

            color: #166534;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;

        }


        /* Account Links */

        .account-link {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px;

            margin-bottom: 10px;

            border-radius: 8px;

            background: #f8fafc;

            color: #2563eb;

            text-decoration: none;

            font-weight: 500;

            transition: 0.3s;

        }


        .account-link:hover {

            background: #eff6ff;

            transform: translateX(4px);

        }


        /* Footer */

        .footer {

            text-align: center;

            color: #9ca3af;

            font-size: 12px;

            margin-top: 35px;

            padding-bottom: 25px;

        }


        /* Mobile */

        @media (max-width: 600px) {

            .navbar {

                padding: 0 20px;

            }


            .logo {

                font-size: 18px;

            }


            .logout {

                padding: 8px 12px;

                font-size: 13px;

            }


            .container {

                width: 92%;

                margin: 25px auto;

            }


            .welcome {

                padding: 24px;

            }


            .welcome h1 {

                font-size: 23px;

            }


            .card {

                padding: 24px;

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
        class="logout"
        href="logout.php"
    >

        🚪 Logout

    </a>


</div>



<!-- Main -->

<div class="container">


    <!-- Welcome -->

    <div class="welcome">

        <h1>

            Welcome,

            <span class="username">

                <?php
                echo htmlspecialchars(
                    $_SESSION['name']
                );
                ?>

            </span>

            👋

        </h1>


        <p>

            You are successfully logged in
            to your account.

        </p>

    </div>



    <!-- Cards -->

    <div class="cards">


        <!-- Profile Card -->

        <div class="card">


            <div class="card-icon">

                👤

            </div>


            <h3>

                My Profile

            </h3>


            <div class="info">

                <strong>Name:</strong><br>

                <?php
                echo htmlspecialchars(
                    $_SESSION['name']
                );
                ?>

            </div>


            <div class="info">

                <strong>Email:</strong><br>

                <?php
                echo htmlspecialchars(
                    $_SESSION['email']
                );
                ?>

            </div>


            <a
                href="profile.php"
                class="account-link"
            >

                👤 View Profile

            </a>


        </div>



        <!-- Account Status -->

        <div class="card">


            <div class="card-icon">

                📊

            </div>


            <h3>

                Account Status

            </h3>


            <div class="info">

                Account Status:

                <br><br>

                <span class="status">

                    Active 🟢

                </span>

            </div>


            <div class="info">

                Login Status:

                <br><br>

                <span class="status">

                    Successful ✓

                </span>

            </div>


        </div>



        <!-- Account -->

        <div class="card">


            <div class="card-icon">

                ⚙️

            </div>


            <h3>

                Account Settings

            </h3>


            <a
                href="profile.php"
                class="account-link"
            >

                👤 View Profile

            </a>


            <a
                href="edit_profile.php"
                class="account-link"
            >

                ✏️ Edit Profile

            </a>


            <a
                href="change_password.php"
                class="account-link"
            >

                🔐 Change Password

            </a>


            <a
                href="logout.php"
                class="account-link"
            >

                🚪 Logout

            </a>


        </div>


    </div>


    <div class="footer">

        My Website © 2026

    </div>


</div>


</body>

</html>
```
