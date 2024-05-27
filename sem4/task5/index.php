<?php
include ("functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include('login.php');
    exit();
}


//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $all_names = ["fio", "telephone", "email", "bday", "sex", "langs", "biography", "contract"];
//    $form_data = array_fill_keys($all_names, "");
//    $form_data['langs'] = [];
//    foreach ($_POST as $key => $val) {
//        if (!empty($val)) {
//            $form_data[$key] = $val;
//        }
//    }
//    foreach ($form_data as $key => $val) {
//        if (gettype($val) == gettype([])) {
//            print($key . ":");
//            foreach ($val as $v) {
//                print("{$v} ");
//            }
//        }
//        else {
//            print("{$key}={$val}|empty=" . empty($val) . " ");
//        }
//    }
//    exit();
//    validate_data($form_data);
//    save_to_database($form_data);
//    setcookie('cor_data', serialize($form_data), time() + 3600 * 24 * 365);
//    header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path'] . "?success_flag=true");




