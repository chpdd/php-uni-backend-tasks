<?php
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

?>

<h1>Войдите в свой аккаунт</h1>
<div class="div-input">
    <label class="black label-center">Логин</label>
    <input id="login" class="size-input" type="text">
</div>
<div class="div-input">
    <label class="black label-center">Пароль</label>
    <input id="password" class="size-input" type="password">
</div>
<div id="div-with-submit">
    <input id="submit-request" class="div_button" type="submit" value="Войти">
</div>
<p>или сгенирирйте случайный логин и пароль</p>
<div id="div-with-submit">
    <input id="submit-request" class="div_button" type="submit" value="Сгенерировать">
</div>