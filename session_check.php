
<?php

session_start();

/*
|--------------------------------------------------------------------------
| Check Login Session
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['email']) ||
    empty($_SESSION['email'])
) {

    header("Location: login.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Check User Name
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['name']) ||
    empty($_SESSION['name'])
) {

    header("Location: login.php");
    exit();

}

?>
