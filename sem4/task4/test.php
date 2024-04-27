<?php
function print_error($error)
{
    print($error);
    exit();
}

function validate_data($data)
{
    $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];
    $size_limits = ["fio" => 255, "email" => 255, "biography" => 512];
    $errors = [];

    foreach ($all_names as $key) {
        if (empty($data[$key])) {
            $errors[$key] = "Поле " . $key . " должно быть заполнено.";
        } elseif (in_array($key, array_keys($size_limits)) && strlen($data[$key]) > $size_limits[$key]) {
            $errors[$key] = "Длина содержимого поля " . $key . " должна быть не более " . $size_limits[$key] . " символов.";
        } elseif ($key == "telephone" && !preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $data[$key])) {
            $errors[$key] = "Некорректный номер телефона.";
        }
    }

    if (!empty($errors)) {
        // Сохраняем ошибки в куки
        setcookie('form_errors', serialize($errors), 0, '/');
        // Перенаправляем на ту же страницу с параметром errors
        header('Location: ' . $_SERVER['REQUEST_URI'] . '?errors');
        exit();
    }
}

function save_to_database($data)
{
    // Реализация сохранения в базу данных
}

// Если есть POST-запрос, то валидируем данные
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = $_POST;
    validate_data($form_data);
    save_to_database($form_data);
}

// Если есть параметр errors в URL, то извлекаем ошибки из куков и выводим их
$errors = [];
if (!empty($_COOKIE['form_errors'])) {
    $errors = unserialize($_COOKIE['form_errors']);
    // Удаляем куки с ошибками
    setcookie('form_errors', '', time() - 3600, '/');
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
    <link rel="shortcut icon" href="../../logo.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Задание 3</title>
    <style>
        .error { border: 1px solid red; }
        .error-message { color: red; }
    </style>
</head>

<body>
<header>
    <nav class="header-logo">
        <img class="header-logo" src="../../logo.png" alt="Logo">
    </nav>
    <nav class="header-info">
        <h2 class="header-title">$$$ website?</h2>
        <h3 class="header-task">Задание 3</h3>
    </nav>
</header>

<main>
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="tasks" id="div_form">
        <form method="post" id="form">
            <!-- Остальная часть вашей формы -->
        </form>
    </div>
</main>

<footer>
    <p class=" text_in_footer">created by</p>
    <h3 class=" text_in_footer">Денис Чупилко</h3>
</footer>
</body>

</html>
