<?php
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
    if (isset($_COOKIE['cor_data'])) {
        $fields_data = unserialize($_COOKIE['cor_data']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="../../jquery.js" defer></script>
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="../../logo.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;200;300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <title>Задание 4</title>
</head>

<body>
<header>
    <nav class="header-logo">
        <img class="header-logo" src="../../logo.png" alt="Logo">
    </nav>
    <nav class="header-info">
        <h2 class="header-title">$$$ website?</h2>
        <h3 class="header-task">Задание 4</h3>
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
            <?php if (!empty($errors)): ?>
                <div class="div-result">
                    <?php foreach ($errors as $key => $val): ?>
                        <div class="error-color label-center">
                            <?php echo $val; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($success_flag)): ?>
                <div class="div-result">
                    <p class="success-color">Данные успешно сохранены, спасибо!</p>
                </div>
            <?php endif; ?>
            <?php echo_form($all_names, $fields_data, $errors); ?>
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