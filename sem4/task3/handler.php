<?php
function print_error($error)
{
    print($error);
    exit();
}

function validate_data($data)
{
    $size_limits = ["fio" => 255, "email" => 255, "biography" => 512];
    foreach ($data as $key => $val)
    {
        if (!isset($val))
        {
            print_error("Field " . $key . " is empty.");
        }
        elseif (in_array($key, array_keys($size_limits))
            && strlen($val) > $size_limits[$key])
        {
            print_error("Length of the contents of the field " . $key . " more than " . $size_limits[$key]
                . " symbols.");
        }
        elseif ($key == "telephone" && !preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $val))
        {
            print_error("Invalid telephone.");
        }
    }
}

function save_to_database($data)
{
    include "hid_vars.php";
    global $user, $password, $database, $host;
    $db = new PDO('mysql:host=' . $host . 'dbname=' . $database, $user, $password,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    try {
        $names_data_for_app = ['fio', 'telephone', 'email', 'bday', 'sex', 'biography'];
        $app_req = "INSERT INTO application (" . implode(', ', $names_data_for_app) .
            ") VALUES (";
        $data_for_app = [];
        foreach ($names_data_for_app as $name)
        {
            $data_for_app[] = $data[$name];
        }
        $app_req = $app_req . implode(', ', $data_for_app) . ");";
        $app_stmt = $db->prepare($app_req);
        $app_stmt->execute();

        $last_app_id = $db->lastInsertId();
        $link_req = "INSERT INTO app_link_lang (id_app, id_prog_lang) VALUES ";
        $data_for_link = [];
        foreach ($data["prog-lang"] as $lang)
        {
            $data_for_link[] = $link_req . "(" . $last_app_id . ", " . $lang . ")";
        }
        $link_req = $link_req . implode(", ", $data_for_link) . ";";
        $link_stmt = $db->prepare($link_req);
        $link_stmt->execute();
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function main()
{
    header('Content-Type: text/html; charset=UTF-8');
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!empty($_GET['save'])) {

            print('Спасибо, результаты сохранены.');
        }
        print("Сработал GET ШО ПРОИСХОДИТ");
        exit();
    }

    $form_data = $_POST;
    validate_data($form_data);
    save_to_database($form_data);
    exit();
}

main();
