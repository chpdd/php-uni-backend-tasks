<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');

include("functions.php");
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1440)) {
    session_unset();
    session_destroy();
}
if (isset($_SESSION['session_active'])) {
    $_SESSION['last_activity'] = time();
}

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
        }
    } elseif (isset($_SESSION['session_active'])) {
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
    if (!empty($_POST['csrf_token']) &&
        hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        if (isset($_POST['admin_edit_form'])) {
            $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

            foreach ($all_names as $name) {
                if (isset($_POST[$name])) {
                    $fields_data[$name] = $_POST[$name];
                }
            }

            validate_data($fields_data);
            $fields_data = masking_data($fields_data);
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
        elseif (isset($_POST['authorization'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            if (admin_in_db($login, $password)) {
                $_SESSION['admin_login'] = $login;
                $_SESSION['admin_password'] = $password;
                $_SESSION['admin_session'] = true;
                header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
            } elseif (user_in_db($login, $password)) {
                $_SESSION['login'] = $login;
                $_SESSION['password'] = $password;
                $_SESSION['session_active'] = true;
                header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
            } else {
                setcookie('authorization_errors', serialize(["User with such login and password was not found"]), 0);
                header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?error_authorization_flag=true");
            }
            exit();
        } elseif (isset($_POST['registration_form'])) {
            $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

            foreach ($all_names as $name) {
                if (isset($_POST[$name])) {
                    $fields_data[$name] = $_POST[$name];
                }
            }

            validate_data($fields_data);
            $fields_data = masking_data($fields_data);
            $_SESSION['session_active'] = true;
            $login = "user_" . random_int(0, 99999);
            $password = generate_password();
            save_to_database($fields_data, $login, $password);
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?registration_flag=True");
            exit();
        } elseif (isset($_POST['authorization_form'])) {
            $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];

            foreach ($all_names as $name) {
                if (isset($_POST[$name])) {
                    $fields_data[$name] = $_POST[$name];
                }
            }

            validate_data($fields_data);
            $fields_data = masking_data($fields_data);
            $login = $_SESSION['login'];
            update_database($fields_data, $login);
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?success_flag=True");
            exit();
        }
    } else {
        header("HTTP/1.1 403 Forbidden");
        echo "Ошибка CSRF";
        exit();
    }
}


