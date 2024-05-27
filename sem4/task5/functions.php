<?php
function print_error($message)
{
    echo "<p style='color: red;'>Ошибка: {$message}</p>";
}

function generate_password()
{
    $alpha = [];
    for ($i = 33; $i < 123; $i++) {
        $alpha[] = chr($i);
    }
    $pass = "";
    for ($c = 0; $c < random_int(8, 20); $c++) {
        $pass .= $alpha[random_int(0, count($alpha))];
    }
    return $pass;
}

function echo_form($all_names, $fields_data, $errors)
{
    $classes = [
        'label' => 'black label-center',
        'input' => 'size-input',
        'div' => 'div-input',
        'div-err' => 'div-input div-error'
    ];
    $label_txt = array_combine($all_names,
        [
            'ФИО',
            'Телефон',
            'Email',
            'Дата рождения',
            'Пол',
            'Любимый язык программирования',
            'Биография'
        ]);


    foreach ($all_names as $name) {
        $div_class = 'div';
        if (in_array($name, array_keys($errors))) {
            $div_class = 'div-err';
        }
        echo "<div class='{$classes[$div_class]}'>";

        if ($name == 'sex') {
            $total_label = "<label class='{$classes['label']}'>";
            $total_label .= "{$label_txt[$name]}</label>";
            echo $total_label;
            echo "<div id='sex-radios' class='label-center'>";
            foreach (['man' => 'Мужской', 'woman' => 'Женский'] as $sex => $txt) {
                $input_str = "<input name='{$name}' value='{$sex}' type='radio'";
                if ($sex == $fields_data['sex']) {
                    $input_str .= "checked";
                }
                $input_str .= ">";
                $label_str = "<label class='{$classes['label']}'>";
                $label_str .= "{$txt}</label>";
                echo $input_str;
                echo $label_str;
            }
            echo "</div>";


        } elseif ($name == 'langs') {
            $label_str = "<label class='{$classes['label']}'>";
            $label_str .= "{$label_txt[$name]}</label>";
            echo $label_str;
            $select_str = "<select name='{$name}[]' class='{$classes['input']}' multiple id='prog-lang'>";
            echo $select_str;
            $lang_names = [
                "Pascal",
                "C",
                "C++",
                "JavaScript",
                "PHP",
                "Python",
                "Java",
                "Haskel",
                "Clojure",
                "Prolog",
                "Scala"
            ];
            for ($i = 0; $i < count($lang_names); $i += 1) {
                $option_str = "<option value='{$i}'";
                if (in_array($i, $fields_data[$name])) {
                    $option_str .= "selected";
                }
                $option_str .= ">{$lang_names[$i]}</option>";
                echo $option_str;
            }
            echo "</select>";


        } elseif ($name == 'biography') {
            $label_str = "<label class='{$classes["label"]}' for='{$name}'>";
            $label_str .= "{$label_txt[$name]}</label>";
            $textarea_str = "<textarea name='{$name}' class='{$classes['input']}'>";
            $textarea_str .= "{$fields_data[$name]}</textarea>";
            echo $label_str;
            echo $textarea_str;


        } else {
            $label_str = "<label class='{$classes["label"]}' for='{$name}'>";
            $label_str .= "{$label_txt[$name]}</label>";
            $input_str = "<input value='{$fields_data[$name]}' name='{$name}' class='{$classes['input']}'";
            if ($name == 'bday') {
                $input_str .= "type='date'";
            }
            $input_str .= ">";
            echo $label_str;
            echo $input_str;
        }

        echo "</div>";
    }
}

function draw_form($all_names, $fields_data, $errors)
{
    include("form.php");
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

function save_user($login, $password_hash)
{
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $user, $password,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $last_app_id = $db->lastInsertId();
        $users_req = "INSERT INTO users (login, password_hash, application_id)";
        $users_req = $users_req . " VALUES ({$login}, {$password_hash}, {$last_app_id});";
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}
