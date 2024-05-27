<?php
function print_error($error)
{
    print($error);
    exit();
}

function validate_data($data)
{
    $errors = [];
    $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];
    $re_patterns = ['fio' => '/^[\w\s]+$/',
        'telephone' => '/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/',
        'email' => '/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/'];
    $size_limits = ['fio' => 255, 'email' => 255, 'biography' => 512];
    foreach ($all_names as $name) {
        if (empty($data[$name])) {
            $errors[$name] = "Field {$name} is empty.";
        } elseif (in_array($name, array_keys($size_limits))
            && strlen($data[$name]) > $size_limits[$name]) {
            $errors[$name] = "Length of the contents of the field {$name} more than {$size_limits[$name]} symbols.";
        } elseif (in_array($name, array_keys($re_patterns))
            && !preg_match($re_patterns[$name], $data[$name])) {
            $errors[$name] = "Invalid {$name}.";
        } elseif ($name == 'bday') {
            if (!strtotime($data[$name]) ||
                strtotime('1900-01-01') > strtotime($data[$name]) ||
                strtotime($data[$name]) > time()) {
                $errors[$name] = "Invalid {$name}.";
            }
        }
    }

    if (!empty($errors)) {
        setcookie('errors', serialize($errors), 0);
        setcookie('incor_data', serialize($data), 0);
        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?errors_flag=true");
        exit();
    }
}

function save_to_database($data)
{
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $user, $password,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $names_data_for_app = ['fio', 'telephone', 'email', 'bday', 'sex', 'biography'];
        $app_req = "INSERT INTO application (" . implode(', ', $names_data_for_app) .
            ") VALUES (";
        $data_for_app = [];
        foreach ($names_data_for_app as $name) {
            $data_for_app[] = "'" . $data[$name] . "'";
        }
        $app_req = $app_req . implode(', ', $data_for_app) . ");";
        $app_stmt = $db->prepare($app_req);
        $app_stmt->execute();

        $last_app_id = $db->lastInsertId();
        $link_req = "INSERT INTO app_link_lang (id_app, id_prog_lang) VALUES ";
        $data_for_link = [];
        foreach ($data["langs"] as $lang) {
            $data_for_link[] = "(" . $last_app_id . ", " . $lang . ")";
        }
        $link_req = $link_req . implode(", ", $data_for_link) . ";";
        $link_stmt = $db->prepare($link_req);
        $link_stmt->execute();
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include('body.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];
    $form_data = array_fill_keys($all_names, "");
    $form_data['langs'] = [];
    foreach ($_POST as $key => $val) {
        if (!empty($val)) {
            $form_data[$key] = $val;
        }
    }
//    foreach ($form_data as $key => $val) {
//        if (gettype($val) == gettype([])) {
//            print($key . ":");
//            foreach ($val as $v) {
//                print("{$v} ");
//            }
//        }
//        else {
//            print("{$key}={$val}|empty=" . empty($val) . " ");
//        }
//    }
//    exit();
    validate_data($form_data);
    save_to_database($form_data);
    setcookie('cor_data', serialize($form_data), time() + 3600 * 24 * 365);
    header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?success_flag=true");
}



