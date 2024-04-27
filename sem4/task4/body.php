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
    <title>Задание 3</title>
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

<aside>
    <nav class="aside-link">
        <a href="../index.html" class="">Задания 4-го семестра</a>
    </nav>
</aside>

<main>
    <div class="tasks" id="div_form">
        <form method="post" id="form">
            <h1 id="h1-form" class="black">Форма</h1>
            <?php if (isset($errors)): ?>
                <div class="div-result">
                    <?php foreach ($errors as $key => $val): ?>
                        <div class="error-color label-center">
                            <?php echo $val; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($success_flag)): ?>
                <div class="div-result">
                    <p class="result-color">Данные успешно сохранены, спасибо!</p>
                </div>
            <?php if (isset($_COOKIE['result'])): ?>
                $result = $_COOKIE['result'];
            <div class="div-input">
                <label id="for-fio" class="black label-center" for="fio">ФИО</label>
                <input value=<?php echo $_COOKIE['fio']?> name="fio" class="size-input" id="fio" type="text">
            </div>

            <div class="div-input">
                <label id="for-telephone" class="black label-center" for="telephone">Телефон</label>
                <input name="telephone" class="size-input" id="telephone" type="tel">
            </div>

            <div class="div-input">
                <label id="for-email" class="black label-center" for="email">Email</label>
                <input name="email" class="size-input" id="email" type="email">
            </div>

            <div class="div-input">
                <label id="for-bday" class="black label-center" for="bday">Дата рождения</label>
                <input name="bday" class="size-input" id="bday" type="date" min='1900-01-01'>
                <script>
                    document.getElementById('bday').max = new Date().toISOString().split('T')[0];
                </script>
            </div>

            <div class="div-input">
                <label id="for-organization" class="black label-center">Пол</label>
                <div id="sex-radios" class="label-center">
                    <input name="sex" class="" id="sex-man" type="radio" value="man">
                    <label id="for-sex-man" class="black label-center">Мужской</label>

                    <input name="sex" class="" id="sex-woman" type="radio" value="woman">
                    <label id="for-sex-woman" class="black label-center">Женский</label>
                </div>
            </div>

            <div class="div-input">
                <label id="for-prog-lang" class="black label-center">Любимый язык программирования</label>
                <select name="langs[]" multiple="multiple" id="prog-lang" class="size-input">
                    <option value="0">Pascal</option>
                    <option value="1">C</option>
                    <option value="2">C++</option>
                    <option value="3">JavaScript</option>
                    <option value="4">PHP</option>
                    <option value="5">Python</option>
                    <option value="6">Java</option>
                    <option value="7">Haskel</option>
                    <option value="8">Clojure</option>
                    <option value="9">Prolog</option>
                    <option value="10">Scala</option>
                </select>
            </div>

            <div class="div-input">
                <label id="for-biography" class="black label-center" for="biography">Биография</label>
                <textarea class="size-input" id="biography" name="biography"></textarea>
            </div>

            <div class="label-center">
                <input id="contract" type="checkbox" name="contract" value="1">
                <label id="for-contract" class="black" for="contract">С контрактом ознакомлен</label>
            </div>

            <div id="div-with-submit">
                <input id="submit-request" class="div_button" type="submit" value="Сохранить">
            </div>
        </form>
        <!--        <script>-->
        <!--            document.getElementById('form').addEventListener('submit', function(event) {-->
        <!--                event.preventDefault();-->
        <!--        </script>-->
    </div>
</main>

<footer>
    <p class=" text_in_footer">created by</p>
    <h3 class=" text_in_footer">Денис Чупилко</h3>
</footer>
</body>

</html>