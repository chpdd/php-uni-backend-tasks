<?php
$admin_login = $_SESSION['admin_login'];
$users = get_all_users();
$prog_langs_statistic = get_prog_langs_statistic();
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
    <title>Задание 6</title>
</head>

<body>
<header>
    <nav class="header-logo">
        <img class="header-logo" src="../../logo.png" alt="Logo">
    </nav>
    <nav class="header-info">
        <h2 class="header-title">$$$ website?</h2>
        <h3 class="header-task">Админ-панель</h3>
    </nav>

</header>

<aside>
    <nav class="aside-link">
        <a href="../index.html" class="">Задания 4-го семестра</a>
    </nav>
</aside>

<main>
    <div class="tasks">
        <h1>Список пользователей</h1>
        <div class="div-input">
            Админ:
            <?php echo $admin_login ?>
        </div>
        <?php
        foreach ($users as $user) {
            $user_login = $user['login'];
            echo "<a href='?user={$user_login}'>{$user_login}</a>";
        }
        ?>
    </div>
    <div class="tasks">
        <h3>Статистика по языкам программирования</h3>
        <?php
        foreach ($prog_langs_statistic as $prog_lang=>$user_count) {
            echo "{$prog_lang}: {$user_count}";
            echo "<br>";
        }
        ?>
    </div>
</main>

<footer>
    <p class=" text_in_footer">created by</p>
    <h3 class=" text_in_footer">Денис Чупилко</h3>
</footer>
</body>

</html>