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