<?php
include("functions.php");

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    authenticate();
} else {
    $users = array(
        'username' => password_hash('your_password', PASSWORD_DEFAULT), // Замените 'your_password' на реальный пароль
        'anotheruser' => password_hash('another_password', PASSWORD_DEFAULT)
    );

    if (!isset($users[$_SERVER['PHP_AUTH_USER']]) ||
        !password_verify($_SERVER['PHP_AUTH_PW'], $users[$_SERVER['PHP_AUTH_USER']])) {
        authenticate();
    } else {
        echo "Welcome, {$_SERVER['PHP_AUTH_USER']}!";
    }
}
?>
