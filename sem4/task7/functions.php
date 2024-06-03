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


function validate_data($data)
{
    $errors = [];
    $all_langs = range(0, 10);
    $all_sexs = ['man', 'woman'];
    $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];
    $re_patterns = ['fio' => '/^[\w\sА-Яа-яЁё]+$/u',
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
        } elseif ($name == 'langs') {
            foreach ($data['langs'] as $lang) {
                if (!in_array($lang, $all_langs)) {
                    $errors[$name] = "Invalid langs";
                }
            }
        } elseif ($name == 'sex') {
            if (!in_array($data['sex'], $all_sexs)) {
                $errors[$name] = 'Invalid sex';
            }
        }
    }

    if (!empty($errors)) {
        setcookie('errors', serialize($errors), 0);
        setcookie('incor_data', serialize($data), 0);
        if (isset($_SESSION['ed_login'])) {
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] .
                "?errors_flag=true&user={$_SESSION['ed_login']}");
        } else {
            header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] .
                "?errors_flag=true&form_flag=true");
        }
        exit();
    }
}

//function validate_user_data($data)
//{
//    $user_errors = [];
//    $all_names = ["login", "password"];
//    foreach ($all_names as $name) {
//        if (empty($data[$name])) {
//            $errors[$name] = "Field {$name} is empty.";
//        } elseif (strlen($data[$name]) > 255) {
//            $errors[$name] = "Length of the contents of the field {$name} more than 255 symbols.";
//        }
//    if (!empty($errors)) {
//        setcookie('errors', serialize($errors), 0);
//        setcookie('incor_data', serialize($data), 0);
//        header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?errors_flag=true");
//        exit();
//    }
//    }


function save_to_database($data, $login, $password_hash) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $app_req = "INSERT INTO application (fio, telephone, email, bday, sex, biography) VALUES (:fio, :telephone, :email, :bday, :sex, :biography)";
        $app_stmt = $db->prepare($app_req);
        $app_stmt->execute([
            ':fio' => $data['fio'],
            ':telephone' => $data['telephone'],
            ':email' => $data['email'],
            ':bday' => $data['bday'],
            ':sex' => $data['sex'],
            ':biography' => $data['biography']
        ]);

        $last_app_id = $db->lastInsertId();

        $login = "user_" . $last_app_id;
        $password = generate_password();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $link_req = "INSERT INTO app_link_lang (id_app, id_prog_lang) VALUES (:id_app, :id_prog_lang)";
        $link_stmt = $db->prepare($link_req);
        foreach ($data['langs'] as $lang) {
            $link_stmt->execute([
                ':id_app' => $last_app_id,
                ':id_prog_lang' => $lang
            ]);
        }

        $users_req = "INSERT INTO users (login, password_hash, application_id) VALUES (:login, :password_hash, :application_id)";
        $users_stmt = $db->prepare($users_req);
        $users_stmt->execute([
            ':login' => $login,
            ':password_hash' => $password_hash,
            ':application_id' => $last_app_id
        ]);

        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function user_in_db($login, $password) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $id_app = (int)substr($login, strpos($login, '_') + 1);

        $find_app_req = "SELECT password_hash FROM users WHERE application_id = :application_id";
        $find_app_stmt = $db->prepare($find_app_req);
        $find_app_stmt->execute([':application_id' => $id_app]);
        $result = $find_app_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $password_hash = $result['password_hash'];
            return password_verify($password, $password_hash);
        } else {
            return false;
        }
    } catch (PDOException $e) {
        print_error($e->getMessage());
        return false;
    }
}

function admin_in_db($login, $password) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $find_admin_req = "SELECT * FROM admins WHERE login = :login";
        $find_admin_stmt = $db->prepare($find_admin_req);
        $find_admin_stmt->execute([':login' => $login]);
        $result = $find_admin_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $password_hash = $result['password_hash'];
            if (password_verify($password, $password_hash)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (PDOException $e) {
        print_error($e->getMessage());
        return false;
    }
}



function get_user_fields_data($login) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $id_app = (int)substr($login, strpos($login, '_') + 1);

        $get_data_req = "SELECT * FROM application WHERE id_app = :id_app";
        $get_data_stmt = $db->prepare($get_data_req);
        $get_data_stmt->execute([':id_app' => $id_app]);
        $result = $get_data_stmt->fetch(PDO::FETCH_ASSOC);

        $get_langs_req = "SELECT id_prog_lang FROM app_link_lang WHERE id_app = :id_app";
        $get_langs_stmt = $db->prepare($get_langs_req);
        $get_langs_stmt->execute([':id_app' => $id_app]);
        $langs_result = $get_langs_stmt->fetchAll(PDO::FETCH_ASSOC);

        $fields_data = [];
        $all_names = ["fio", "telephone", "email", "bday", "sex", "biography"];
        foreach ($all_names as $name) {
            if (isset($result[$name])) {
                $fields_data[$name] = $result[$name];
            }
        }
        foreach ($langs_result as $row) {
            $lang = $row['id_prog_lang'];
            $fields_data['langs'][] = $lang;
        }
        return $fields_data;
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function update_database($fields_data, $login) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $id_app = (int)substr($login, strpos($login, '_') + 1);
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $names_data_for_app = ['fio', 'telephone', 'email', 'bday', 'sex', 'biography'];
        $update_app_req = "UPDATE application SET ";
        $update_fields = [];
        $update_params = [':id_app' => $id_app];

        foreach ($names_data_for_app as $name) {
            $update_fields[] = "{$name} = :{$name}";
            $update_params[":{$name}"] = $fields_data[$name];
        }
        $update_app_req .= implode(', ', $update_fields);
        $update_app_req .= " WHERE id_app = :id_app";

        $update_app_stmt = $db->prepare($update_app_req);
        $update_app_stmt->execute($update_params);

        $del_links_req = "DELETE FROM app_link_lang WHERE id_app = :id_app";
        $del_links_stmt = $db->prepare($del_links_req);
        $del_links_stmt->execute([':id_app' => $id_app]);

        $link_req = "INSERT INTO app_link_lang (id_app, id_prog_lang) VALUES ";
        $data_for_link = [];
        foreach ($fields_data["langs"] as $lang) {
            $data_for_link[] = "(:id_app, :id_prog_lang_{$lang})";
            $link_params[":id_prog_lang_{$lang}"] = $lang;
        }
        $link_req .= implode(", ", $data_for_link);

        $link_stmt = $db->prepare($link_req);
        $link_stmt->execute(array_merge([':id_app' => $id_app], $link_params));
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function get_all_users()
{
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $get_users_req = "SELECT * FROM users;";
        $get_users_stmt = $db->prepare($get_users_req);
        $get_users_stmt->execute();
        $users_db = $get_users_stmt->fetchAll(PDO::FETCH_ASSOC);

        $users_result = [];
        foreach ($users_db as $user_db) {
            $user = [
                'id' => $user_db['id'],
                'login' => $user_db['login'],
                'application_id' => $user_db['application_id']
            ];
            $users_result[] = $user;
        }
        return $users_result;
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function get_prog_langs_statistic()
{
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $get_statistic_req = "
            SELECT 
                pl.name_prog_lang AS prog_lang, 
                COUNT(al.id_app) AS user_count
            FROM 
                prog_lang pl
            LEFT JOIN 
                app_link_lang al ON pl.id_prog_lang = al.id_prog_lang
            GROUP BY 
                pl.name_prog_lang, pl.id_prog_lang
            ORDER BY 
                pl.id_prog_lang;
            ";
        $get_statistic_stmt = $db->prepare($get_statistic_req);
        $get_statistic_stmt->execute();
        $statisitic_rows = $get_statistic_stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($statisitic_rows as $row) {
            $result[$row['prog_lang']] = $row['user_count'];
        }

        return $result;
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function delete_user($login) {
    include("../hid_vars.php");
    $db_req = "mysql:dbname={$database};host={$host}";
    try {
        $db = new PDO($db_req, $db_user, $db_password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $find_user_req = "SELECT application_id FROM users WHERE login = :login";
        $find_user_stmt = $db->prepare($find_user_req);
        $find_user_stmt->execute([':login' => $login]);
        $user_result = $find_user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_result) {
            $application_id = $user_result['application_id'];

            $delete_user_req = "DELETE FROM application WHERE id_app = :application_id";
            $delete_user_stmt = $db->prepare($delete_user_req);
            $delete_user_stmt->execute([':application_id' => $application_id]);
        }
    } catch (PDOException $e) {
        print_error($e->getMessage());
    }
}

function masking_data($data) {
    $all_names = ["fio", "telephone", "email", "bday", "biography", "contract"];
    $new_data = ['langs' => $data['langs'], 'sex' => $data['sex']];
    foreach ($all_names as $name) {
        $data_field = htmlspecialchars($data[$name], ENT_QUOTES, 'UTF-8');
        $new_data[$name] = $data_field;
    }
    return $new_data;
}
