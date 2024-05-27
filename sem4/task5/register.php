<?php
$all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography"];
$fields_data = array_fill_keys($all_names, "");
$fields_data['langs'] = [];
$errors = [];
if (isset($_GET['errors_flag'])) {
    $errors = unserialize($_COOKIE['errors']);
    $fields_data = unserialize($_COOKIE['incor_data']);
//    foreach (unserialize($_COOKIE['incor_data'])['langs'] as $lang)
//    {
//        print($lang . " ");
//    }
} else {
    if (isset($_GET['success_flag'])) {
        $success_flag = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($all_names as $name) {
        if (isset($_POST[$name])) {
            $fields_data[$name] = $_POST[$name];
        }
    }

    validate_data($fields_data);

    if (empty($errors)) {
        session_start();
        $login = "user_" . random_int(0, 99999);
        $password = generate_password();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        save_to_database($fields_data);
        save_user($login, $password_hash);
        //здесь надо сделать пересылку куда-то и там проверка на авторизацию
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="../../jquery.js" defer></script>
    <script src="scripts.js" defer></script>
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="../../logo.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;200;300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <title>Задание 5</title>
</head>

<body>
<header>
    <nav class="header-logo">
        <img class="header-logo" src="../../logo.png" alt="Logo">
    </nav>
    <nav class="header-info">
        <h2 class="header-title">$$$ website?</h2>
        <h3 class="header-task">Задание 5</h3>
    </nav>

</header>

<aside>
    <nav class="aside-link">
        <a href="../index.html" class="">Задания 4-го семестра</a>
    </nav>
</aside>

<main>
    <?php draw_form($all_names, $fields_data, $errors); ?>
</main>

<footer>
    <p class=" text_in_footer">created by</p>
    <h3 class=" text_in_footer">Денис Чупилко</h3>
</footer>
</body>

</html>>
