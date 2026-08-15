<?php

session_start();

// सभी session variables हटाओ
$_SESSION = [];

// Session cookie भी हटाओ
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Session पूरी तरह destroy करो
session_destroy();

// Login page पर भेजो
header("Location: login.php");
exit();

?>