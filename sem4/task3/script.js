function isTelephone(str) {
    if (str in (/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/)) {
        return true;
    } else {
        return false;
    }
}

function main() {
    if (!isTelephone($("telephone").val())) {
        alert("Введите корректный номер телефона")
    }
}

$(document).ready(main());