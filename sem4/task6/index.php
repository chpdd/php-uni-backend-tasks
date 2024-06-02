<?php
include("functions.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_SESSION['admin_session'])) {
        if (isset($_GET['user'])) {
            include("admin_edit.php");
        } else {
            include("admin.php");
        }
    } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $admin_login = $_SERVER['PHP_AUTH_USER'];
        $admin_password = $_SERVER['PHP_AUTH_PW'];
        if (admin_in_db($admin_login, $admin_password)) {
            $_SESSION['admin_login'] = $admin_login;
            $_SESSION['admin_password'] = $admin_password;
            $_SESSION['admin_session'] = true;
            include("admin.php");
        } else {
            authenticate();
        }
    } else {
        authenticate();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['admin_edit_form'])) {
        $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

        foreach ($all_names as $name) {
            if (isset($_POST[$name])) {
                $fields_data[$name] = $_POST[$name];
            }
        }
        validate_data($fields_data);
        $ed_login = $_SESSION['ed_login'];
        update_database($fields_data, $ed_login);
        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?success_flag=True&user={$ed_login}");
        exit();
    } elseif (isset($_POST['delete_user'])) {
        $ed_login = $_SESSION['ed_login'];
        delete_user($ed_login);
        unset($_SESSION['ed_login']);
        unset($_SESSION['session_active']);
        unset($_SESSION['login']);
        unset($_SESSION['password']);
        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
        exit();
    }
}


