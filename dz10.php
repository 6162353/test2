<?php

/*
 * Задание dz9.php (mysqli) переделать с помощью DbSimple, 
 * все запросы к БД должны выводиться отладочным механизмом через FirePHP 
 * и видны в консоли Firebug
 * 
 */


$project_root = $_SERVER['DOCUMENT_ROOT'];

$site_dir = '/test2';

$smarty_dir = $project_root . $site_dir . '/smarty/';

require_once $project_root . $site_dir . '/dbsimple/lib/config.php';
require_once $project_root . $site_dir . '/dbsimple/lib/DbSimple/Generic.php';

require_once $project_root . $site_dir . '/FirePHPCore/FirePHP.class.php';

$firePHP = FirePHP::getInstance(true);

$firePHP->setEnabled(true);



// put full path to Smarty.class.php
require($smarty_dir . 'libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;



$smarty->template_dir = $smarty_dir . 'templates';
$smarty->compile_dir = $smarty_dir . 'templates_c';
$smarty->cache_dir = $smarty_dir . 'cache';
$smarty->config_dir = $smarty_dir . 'configs';

header('Content-type: text/html; charset=utf-8');


$current_php_script = 'dz10';


$seller_name = "";
$checkedPrivate = 'checked';
$checkedCompany = '';
$post_edit = 0;
$email = '';
$checked_allow_mails = '';
$phone = $title = $description = '';
$selected = 'selected=""';
$location_id = '641780';
$price = '0';
$amount_ads = 0;
$db_user = 'dz9';
$db_pass = 'dz9';
$db_name = 'dz9';
$db_server = 'localhost';


$db = DbSimple_Generic::connect('mysqli://' . $db_user . ':' . $db_pass . '@' . $db_server . '/' . $db_name);



$db->setErrorHandler('databaseErrorHandler');
$db->setLogger('myLogger');

function databaseErrorHandler($message, $info) {
    if (!error_reporting())
        return;
    echo "SQL Error: $message<br><pre>";
    print_r($info);
    echo "</pre>";
    exit();
}

function myLogger($db, $sql, $caller) {
    global $firePHP;


    if (isset($caller['file'])) {

        $firePHP->group("at " . @$caller['file'] . ' line ' . @$caller['line']);
    }

    $firePHP->log($sql);


    if (isset($caller['file'])) {

        $firePHP->groupEnd();
    }
}

$result = $db->select('select * from cities order by id ASC');


foreach ($result as $value) {

    $cities[$value['city']] = $value['id'];
}

$firePHP->log($cities, '$cities');


$tube_station_id = '';

/* МЕТРО $tube_stations  */

$result = $db->select('select * from tube_stations order by tube_station ASC');

foreach ($result as $value) {

    $tube_stations[$value['tube_station']] = $value['id'];
}


$firePHP->log($tube_stations, '$tube_stations');



$category_id = '';

$result = $db->select('select * from categories order by id ASC');
$firePHP->log($result, 'categories $result');

foreach ($result as $value) {

    $result2 = $db->select('select * from subcategories where category=' . $value['id'] . ' order by subcategory');
    $firePHP->log($result2, 'subcategories $result2');

    foreach ($result2 as $value2) {

        $subcategory[$value2['subcategory']] = $value2['id'];
    }


    $categories[$value['category']] = $subcategory;
    $subcategory = array();
}

$firePHP->log($categories, '$categories');

/*
  через бд
 */

// Получаем объявления из бд


if ($result = $db->select('select * from ads order by id ASC')) {

    $temp_array = $result;
} else {

    $temp_array = array();
}

$firePHP->log($temp_array, 'ads from db $temp_array');



if (isset($_POST['form'])) {
    if ($_POST['form'] == "Записать изменения") {
// сохранить элемент
// записать изменение в базу

        if (isset($_POST['allow_mails'])) {

            $allow_mails = $_POST['allow_mails'];
        } else {

            $allow_mails = '0';
        }

        //Изменили значение

        $db->query('UPDATE ads SET ' .
                'title=?, price=?, user_name=? , 
        email=?, tel=?, descr=?,
        id_city=? , id_tube_station=? , id_subcategory=? ,
        private=?, send_to_email=? WHERE id=?', $_POST['title'], $_POST['price'], $_POST['seller_name'], $_POST['email'], $_POST['phone'], $_POST['description'], $_POST['location_id'], $_POST['metro_id'], $_POST['category_id'], $_POST['private'], $allow_mails, $_GET['id']);

        // обновляем в temp_array

        $row = $db->selectRow('select * from ads where ads.id=?', $_GET["id"]);

        $firePHP->log($row, '$row');


        foreach ($temp_array as $key => $value) {

            if ($temp_array[$key]['id'] == $_GET["id"]) {

                $temp_array[$key] = $row;
            }
        }

        $firePHP->log($temp_array, 'ads $temp_array');



        $_POST = null;
        header('Location:' . $site_dir . '/' . $current_php_script . '.php');
    }
    if ($_POST['form'] == "Назад") {
        $_POST = null;
        unset($_GET);
        header('Location:' . $site_dir . '/' . $current_php_script . '.php');
    }
}

// если гет заполнен, значит запросили удаление или просмотр
if (isset($_GET["id"])) {
    if (isset($_GET["del"])) {

        $db->query('delete from ads where ads.id=?', $_GET["id"]);

        foreach ($temp_array as $key => $value) {


            if ($temp_array[$key]['id'] == $_GET["id"]) {

                unset($temp_array[$key]);
            }
        }

        $firePHP->log($temp_array, 'ads $temp_array');


        unset($_GET["id"]);
        header('Location:' . $site_dir . '/' . $current_php_script . '.php');
    }


    if (isset($_GET["edit"])) {

        $id = $_GET['id'];
        $post_edit = 1;

        foreach ($temp_array as $value) {


            if ($value['id'] == $id) {

                if ($value['private'] == '1') {

                    $checkedPrivate = 'checked';
                    $checkedCompany = '';
                } else {

                    $checkedPrivate = '';
                    $checkedCompany = 'checked';
                }

                $seller_name = $value['user_name'];
                $email = $value['email'];

                if ($value['send_to_email'] == '0' or $value['send_to_email'] == '') {

                    $checked_allow_mails = '';
                } else {

                    $checked_allow_mails = 'checked';
                }


                $phone = $value['tel'];

                $location_id = $value['id_city'];
                $tube_station_id = $value['id_tube_station'];
                $category_id = $value['id_subcategory'];
                $title = $value['title'];
                $description = $value['descr'];
                $price = $value['price'];
            }
        }
    }
}
// если заполнен пост
elseif (count($_POST)) {
    if (isset($_POST['main_form'])) {
        if ($_POST['main_form'] == 'Добавить') {


            // allow_mails приходит от формы только тогда, когда установлен checkbox
            // а так вообще нет этой переменной, если он не установлен.
            if (isset($_POST['allow_mails'])) {

                $allow_mails = $_POST['allow_mails'];
            } else {

                $allow_mails = '';
            }

            //вставили значение

            $mysql_last_id = $db->query('INSERT into ads ' .
                    '(title, price, user_name, email, tel, descr, id_city, ' .
                    'id_tube_station, id_subcategory, private, send_to_email) ' .
                    'VALUES (?, ?, ?,   ?, ?, ?,    ?, ?, ?,   ?, ? )', $_POST['title'], $_POST['price'], $_POST['seller_name'], $_POST['email'], $_POST['phone'], $_POST['description'], $_POST['location_id'], $_POST['metro_id'], $_POST['category_id'], $_POST['private'], $allow_mails);

            // добавляем к temp_array вставленное значение, для мгновенного отображения

            $firePHP->log($mysql_last_id, '$mysql_last_id');

            $row = $db->selectRow('SELECT * from ads WHERE id=?', $mysql_last_id);

            $firePHP->log($row, '$row');


            $temp_array[] = $row;

            $firePHP->log($temp_array, 'ads $temp_array');

        }
    }
}

// без этого кода объявления не отображаются

if (isset($temp_array)) {


    $amount_ads = count($temp_array);
}


$smarty->assign('checkedPrivate', $checkedPrivate);
$smarty->assign('checkedCompany', $checkedCompany);
$smarty->assign('seller_name', $seller_name);
$smarty->assign('email', $email);
$smarty->assign('checked_allow_mails', $checked_allow_mails);
$smarty->assign('phone', $phone);
$smarty->assign('selected', $selected);
$smarty->assign('cities', $cities);
$smarty->assign('location_id', $location_id);
$smarty->assign('tube_stations', $tube_stations);
$smarty->assign('tube_station_id', $tube_station_id);
$smarty->assign('categories', $categories);
$smarty->assign('category_id', $category_id);
$smarty->assign('title', $title);
$smarty->assign('description', $description);
$smarty->assign('price', $price);
$smarty->assign('post_edit', $post_edit);
$smarty->assign('amount_ads', $amount_ads);
$smarty->assign('temp_array', $temp_array);
$smarty->assign('current_php_script', $current_php_script);
$smarty->assign('site_dir', $site_dir);


$smarty->display('dz9.tpl');
?>



