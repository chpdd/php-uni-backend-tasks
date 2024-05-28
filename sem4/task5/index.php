<?php
include("functions.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_SESSION['session_active'])) {
        include("edit.php");
    } elseif (isset($_GET['registration'])) {
        include("registration.php");
    } elseif (isset($_GET['form_flag'])) {
        include("registration.php");
    } else {
        include("login.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['authorization'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        if (user_in_db($login, $password)) {
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['session_active'] = true;
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
            exit();
        } else {
            setcookie('authorization_errors', serialize(["User with such login and password was not found"]), 0);
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?error_authorization_flag=true");
            exit();
        }
    }
    if (isset($_POST['registration_form'])) {
        $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

        foreach ($all_names as $name) {
            if (isset($_POST[$name])) {
                $fields_data[$name] = $_POST[$name];
            }
        }

        validate_data($fields_data);
        $_SESSION['session_active'] = true;
        $login = "user_" . random_int(0, 99999);
        $password = generate_password();
        save_to_database($fields_data, $login, $password);
//        var_dump($login);
//        var_dump($password);
//        var_dump($password_hash);
//        exit();
        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?registration_flag=True");
        exit();
    }
    if (isset($_POST['authorization_form'])) {
        $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

        foreach ($all_names as $name) {
            if (isset($_POST[$name])) {
                $fields_data[$name] = $_POST[$name];
            }
        }

        validate_data($fields_data);
        $login = $_SESSION['login'];
        update_database($fields_data, $login);
        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?success_flag=True");
    }
}


