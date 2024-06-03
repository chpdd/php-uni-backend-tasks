<?php
$csrf_token = $_SESSION['csrf_token'];
$authorization_errors = [];
if (isset($_GET['error_authorization_flag'])) {
    $error_authorization_flag = true;
    $authorization_errors = unserialize($_COOKIE['authorization_errors']);
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
    <title>Задание 7</title>
</head>

<body>
<header>
    <nav class="header-logo">
        <img class="header-logo" src="../../logo.png" alt="Logo">
    </nav>
    <nav class="header-info">
        <h2 class="header-title">$$$ website?</h2>
        <h3 class="header-task">Задание 7</h3>
    </nav>

</header>

<aside>
    <nav class="aside-link">
        <a href="../index.html" class="">Задания 4-го семестра</a>
    </nav>
</aside>

<main>
    <div class="tasks">
        <h1>Авторизация</h1>
        <?php if (isset($error_authorization_flag)): ?>
            <div class="div-result">
                <?php foreach ($authorization_errors as $key => $val): ?>
                    <div class="error-color label-center">
                        <?php echo $val; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form name="login-form" id="login-form" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="div-input">
                <label id="for-login" class="black label-center" for="login">Логин</label>
                <input name="login" class="size-input" id="login" type="text">
            </div>
            <div class="div-input">
                <label id="for-password" class="black label-center" for="password">Пароль</label>
                <input name="password" class="size-input" id="password" type="password">
            </div>
            <div class="div-input">
                <button name="authorization" value="True">Войти</button>
            </div>
        </form>
        <h2>Регистрация</h2>
        <form name="registration-form" id="registration-form" method="get">
            <div class="div-input">
                <button name="registration" value="True">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p class=" text_in_footer">created by</p>
    <h3 class=" text_in_footer">Денис Чупилко</h3>
</footer>
</body>

</html>