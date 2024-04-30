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
    $symb_patterns = [''];
    $re_patterns = ['fio' => '/^[\w\s]+$/',
        'telephone' => '/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/',
        'email' => '/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/'];
    $size_limits = ['fio' => 255, 'email' => 255, 'biography' => 512];
    foreach ($all_names as $key) {
        if (empty($data[$key])) {
            $errors[$key] = "Field " . $key . " is empty.";
            continue;
        } elseif (in_array($key, array_keys($size_limits))
            && strlen($data[$key]) > $size_limits[$key]) {
            $errors[$key] = "Length of the contents of the field " . $key . " more than " . $size_limits[$key]
                . " symbols.";
            continue;
        } elseif (in_array($key, array_keys($re_patterns)) && !preg_match($re_patterns[$key], $data[$key])) {
            $errors[$key] = "Invalid " . $key;
        }
    }
    if (!empty($errors)) {
        setcookie('errors', serialize($errors), 0);
        setcookie('incor_data', serialize($data), 0);
        header('Location: ' . parse_url($_SERVER['REQUEST_URI'])['path'] . '?errors_flag=true');
    }
}

function save_to_database($data)
{
    include("../hid_vars.php");
    $db_req = 'mysql:dbname=' . $database . ';host=' . $host;
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
//    foreach ($form_data as $key => $val)
//    {
//        print($key . " " . $val);
//    }
//    exit();
    $form_data['langs'] = [];
    foreach ($_POST as $key => $val) {
        $form_data[$key] = $val;
    }
    validate_data($form_data);
    save_to_database($form_data);
    unset($_COOKIE['last_cor_data']);
    setcookie('last_cor_data', serialize($form_data), 3600 * 24 * 365);
    header('Location: ' . parse_url($_SERVER['REQUEST_URI'])['path'] . '?success_flag=true');
}



