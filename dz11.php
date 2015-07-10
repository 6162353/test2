<?php

/*
Задание dz10.php переделать с помощью объектов.
    При создании нового объявления – создается новый объект. 
 * Его можно сохранять, изменять, удалять, выводить на экран (его свойства)
 * 
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


$current_php_script = 'dz11';

class Ad {
    
    /*public $title='';
    public $price='0';
    public $seller_name='';
    public $email='';
    public $phone='';
    public $description='';
    public $location_id='641780';
    public $metro_id='';
    public $category_id='';
    public $private='';
    public $allow_mails=''; */
    
    public $ad = array ('title' => '', 'price' => '0',
            'seller_name'=>'', 'email'=> '', 'phone' => '',
        'description'=>'', 'location_id' => '641780', 'metro_id'=> '',
        'category_id' => '', 'private' => '', 'allow_mails' => '');
    


function create($title,$price,$seller_name,
        $email,$phone,$description,
        $location_id, $metro_id, $category_id,
        $private, $allow_mails) {
    

    
    
    $this->ad['title'] = $title;
    $this->ad['price'] = $price;
    $this->ad['seller_name'] = $seller_name;       
    $this->ad['email'] = $email;
    $this->ad['phone'] = $phone;          
    $this->ad['description'] = $description;
    $this->ad['location_id'] = $location_id;       
    $this->ad['metro_id'] = $metro_id;
    $this->ad['category_id'] = $category_id;  
    $this->ad['private'] = $private;
    $this->ad['allow_mails']=$allow_mails;  
    
} 

function get() {
    
    /*foreach ($this->ad as $key) {
        
        echo $key.'=';
        var_dump($key);
        
    } */
    var_dump($this->ad);
    
}
    
}



class Ads {
    
    
    
    public $ads = array ();
    
    
    
    
    
    // получение объявлений
    function get_ads($db) {
        
        
        if ($result = $db->select('select * from ads order by id ASC')) {

    $this->ads = $result;
            }
    
    return $this->ads;
        
    }
    
    
    
    // добавление объявления
    
    
    function add_ad($db, $POST) {
        
                    if (isset($POST['allow_mails'])) {

                $allow_mails = $POST['allow_mails'];
            } else {

                $allow_mails = '';
            }
            
            $ad1 = new Ad();
            
            $this->ads[] = $ad1->create($_POST['title'], $_POST['price'],
                    $_POST['seller_name'], 
                    $_POST['email'], $_POST['phone'], $_POST['description'], 
                    $_POST['location_id'], $_POST['metro_id'], $_POST['category_id'], 
                    $_POST['private'],$allow_mails);
            
            //вставили значение

            $mysql_last_id = $db->query('INSERT into ads ' .
                    '(title, price, user_name, email, tel, descr, id_city, ' .
                    'id_tube_station, id_subcategory, private, send_to_email) ' .
                    'VALUES (?, ?, ?,   ?, ?, ?,    ?, ?, ?,   ?, ? )', 
                    $_POST['title'], $_POST['price'], $_POST['seller_name'], 
                    $_POST['email'], $_POST['phone'], $_POST['description'], 
                    $_POST['location_id'], $_POST['metro_id'], $_POST['category_id'], 
                    $_POST['private'], $allow_mails);
     
        return $mysql_last_id;
        
    }
    
    function change_ad($db, $POST, $id) {
        
                if (isset($POST['allow_mails'])) {

            $allow_mails = $POST['allow_mails'];
        } else {

            $allow_mails = '0';
        }

        //Изменили значение

        $db->query('UPDATE ads SET ' .
                'title=?, price=?, user_name=? , 
        email=?, tel=?, descr=?,
        id_city=? , id_tube_station=? , id_subcategory=? ,
        private=?, send_to_email=? WHERE id=?', 
                $POST['title'], $POST['price'], $POST['seller_name'], 
                $POST['email'], $POST['phone'], $POST['description'], 
                $POST['location_id'], $POST['metro_id'], $POST['category_id'], 
                $POST['private'], $allow_mails, $id);

        // обновляем в temp_array

        $row = $db->selectRow('select * from ads where ads.id=?', $id);

        //$firePHP->log($row, '$row');


        foreach ($this->ads as $key => $value) {

            if ($this->ads[$key]['id'] == $id) {

                $this->ads[$key] = $row;
            }
        }
    
    return $this->ads;
        
    }
    
    
    function delete_ad($db, $id) {
        
                $db->query('delete from ads where ads.id=?', $id);

        foreach ($this->ads as $key => $value) {


            if ($this->ads[$key]['id'] == $id) {

                unset($this->ads[$key]);
            }
        }
        
    }
    
}


$Ads1 = new Ads();

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


$temp_array = $Ads1->get_ads($db);

$firePHP->log($temp_array, 'ads from db $temp_array');



if (isset($_POST['form'])) {
    if ($_POST['form'] == "Записать изменения") {
// сохранить элемент
// записать изменение в базу

       
        $temp_array = $Ads1->change_ad($db,$_POST, $_GET["id"] );

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
        
        $temp_array = $Ads1->delete_ad($db, $_GET["id"] );


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

            

            //вставили объявление

            $mysql_last_id = $Ads1->add_ad($db, $_POST);
                    

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

// Ads->show();

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



