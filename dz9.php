<?php

/* dz9.php
 * 
 Задание dz_8.php переделать с помощью хранения информации в БД
    Для категорий и городов сделать отдельные таблицы
    Для каждого объявления использовать одну строку в БД

    Затем сдаете задачу в планфиксе.
    Затем выполняете всё с помощью модуля mysqli

    Затем снова сдаете задачу в планфиксе.
 */


$project_root=$_SERVER['DOCUMENT_ROOT'];

$smarty_dir=$project_root.'/test/smarty/';

// put full path to Smarty.class.php
require($smarty_dir.'libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;



$smarty->template_dir = $smarty_dir.'templates';
$smarty->compile_dir = $smarty_dir.'templates_c';
$smarty->cache_dir = $smarty_dir.'cache';
$smarty->config_dir = $smarty_dir.'configs';

header('Content-type: text/html; charset=utf-8');


$current_php_script='dz9';


$seller_name="";
$checkedPrivate='checked';
$checkedCompany='';
$post_edit=0;
$email='';
$checked_allow_mails='';
$phone=$title=$description='';
$selected='selected=""';
$location_id='641780';
$price='0';
$amount_ads=0;
$db_user='dz9';
$db_name='dz9';
$db_server='localhost';

$mysql_last_id='';


$conn = mysql_connect(
$db_server, $db_user,$db_user)
or die("Невозможно установить соединение: ". mysql_error());

mysql_select_db($db_name);
mysql_query('SET NAMES utf8');


$query='select * from cities order by id ASC';

$result_query = mysql_query($query) or die('Запрос не удался');

while ($result = mysql_fetch_assoc($result_query)) {
    
    $cities[$result['city']]=$result['id'];
}



$tube_station_id='';

/* МЕТРО $tube_stations  */

$query='select * from tube_stations order by tube_station ASC';

$result_query = mysql_query($query) or die('Запрос не удался');

while ($result = mysql_fetch_assoc($result_query)) {
$tube_stations[$result['tube_station']]=$result['id'];
}





$category_id='';


$query='select * from categories order by id ASC';
$result_query = mysql_query($query) or die('Запрос не удался');
while ($result = mysql_fetch_assoc($result_query)) {

$subquery='select * from subcategories where category='.$result['id'].' order by subcategory';
$result_subquery = mysql_query($subquery) or die('Запрос не удался');


    while ($result2 = mysql_fetch_assoc($result_subquery)) {

    $subcategory[$result2['subcategory']]=$result2['id'];

    }

$categories[$result['category']]=$subcategory;

//обнуляем
mysql_free_result($result_subquery);
$subcategory=array();


}



/*
через бд
*/

// Получаем объявления из бд

$query='select * from ads order by id ASC';

$result_query = mysql_query($query) or die('Запрос из ads не удался');

if ($result = mysql_fetch_assoc($result_query)) {
    
$temp_array[]=$result;

while ($result = mysql_fetch_assoc($result_query)) {
    $temp_array[]=$result;
    }
}

else {

$temp_array=array();

        }
        

        
        

if (isset($_POST['form'])) {
    if ($_POST['form']=="Записать изменения") {
// сохранить элемент

// записать изменение в базу

if (isset($_POST['allow_mails'])) {
            
        $allow_mails=$_POST['allow_mails'];
        }
        
        else {
            
            $allow_mails='0';
            
        }

        //Изменили значение
        
        $query='UPDATE ads SET '.
        'title="'.$_POST['title'].'", price="'.$_POST['price']. 
        '", user_name="'.$_POST['seller_name'].'", email="'.$_POST['email'].
        '", tel="'.$_POST['phone'].'", descr="'.$_POST['description'].
        '", id_city="'.$_POST['location_id'].'", id_tube_station="'.$_POST['metro_id'].
        '", id_subcategory="'.$_POST['category_id'].'", private="'.$_POST['private'].
        '", send_to_email="'.$allow_mails.  
        '" WHERE id='.$_GET['id'].';';
        
        $result_query = mysql_query($query) or die('Изменение не удалось');
        
        // обновляем в temp_array
        
        $query='select * from ads where ads.id='.$_GET["id"].';';

$result_query = mysql_query($query) or die('Получение измененного элемента не удалось');        
        

foreach ($temp_array as $key => $value) {
        
        if ($temp_array[$key]['id']==$_GET["id"]) {
            
            $temp_array[$key]=mysql_fetch_assoc($result_query);
            
        }
    }
       
        
        
$_POST=null;
}
    if ($_POST['form']=="Назад") {
$_POST=null;
unset($_GET);
header('Location:/test/'.$current_php_script.'.php');
}
}

// если гет заполнен, значит запросили удаление или просмотр
if (isset($_GET["id"])) {
    if (isset($_GET["del"])) {

$query='delete from ads where ads.id='.$_GET["id"].';';

$result_query = mysql_query($query) or die('Удаление выбранного элемента не удалось');        
        

foreach ($temp_array as $key => $value) {
    
    
        if ($temp_array[$key]['id']==$_GET["id"]) {
            
            unset($temp_array[$key]);
            
        }
    }
   

unset($_GET["id"]);
header('Location:/test/'.$current_php_script.'.php');


  

}


    if (isset($_GET["edit"])) {
        
        $id=$_GET['id'];
        $post_edit=1;
        
        foreach ($temp_array as $value) {
            
            
        if ($value['id']==$id) {
           
        if ($value['private']=='1') {
            
            $checkedPrivate = 'checked';
            $checkedCompany = '';
        }
        
        else {
            
                $checkedPrivate = '';
            $checkedCompany = 'checked';
            
        }
        
        $seller_name = $value['user_name'];
        $email= $value['email'];
        
        if ($value['send_to_email']=='0' or $value['send_to_email']=='' ) {
            
            $checked_allow_mails = '';
        }
        
        else {
            
            $checked_allow_mails = 'checked';
            
        }
        
 
        $phone=$value['tel'];
        
        $location_id=$value['id_city'];
        $tube_station_id=$value['id_tube_station'];
        $category_id=$value['id_subcategory'];
        $title=$value['title'];
        $description=$value['descr'];
        $price=$value['price'];
        }
        
        }

}
}
// если заполнен пост

    elseif (count($_POST)) {
if (isset($_POST['main_form'])) {
if ($_POST['main_form']=='Добавить') {
        
   
        // allow_mails приходит от формы только тогда, когда установлен checkbox
        // а так вообще нет этой переменной, если он не установлен.
        if (isset($_POST['allow_mails'])) {
            
        $allow_mails=$_POST['allow_mails'];
        }
        
        else {
            
            $allow_mails='';
            
        }

        //вставили значение
        
        $query='INSERT into ads '.
        '(title, price, user_name, email, tel, descr, id_city, '.
        'id_tube_station, id_subcategory, private, send_to_email) '.
        'VALUES ("'.$_POST['title'].'", "'.$_POST['price'].'", "'.$_POST['seller_name'].'", "'
        .$_POST['email'].'", "'.$_POST['phone'].'", "'.$_POST['description'].'", "'
        .$_POST['location_id'].'", "'.$_POST['metro_id'].'", "'.$_POST['category_id'].'", "'
        .$_POST['private'].'", "'.$allow_mails.'" );';
        

        $result_query = mysql_query($query) or die('Вставка в ads не удалась');
        
        
        // добавляем к temp_array вставленное значение, для мгновенного отображения
        
        
        $mysql_last_id=mysql_insert_id();
        $query='SELECT * from ads WHERE id='.$mysql_last_id.';';
        $result_query = mysql_query($query) or die('Запрос из ads последнего объявления не удался');
        $temp_array[]= mysql_fetch_assoc($result_query); 
        
   

}
}

}

if (isset($temp_array)) {
        

      $amount_ads=count($temp_array); 

}


$smarty->assign('checkedPrivate',$checkedPrivate);
$smarty->assign('checkedCompany',$checkedCompany);
$smarty->assign('seller_name',$seller_name);
$smarty->assign('email',$email);
$smarty->assign('checked_allow_mails',$checked_allow_mails);
$smarty->assign('phone',$phone);
$smarty->assign('selected',$selected);
$smarty->assign('cities', $cities);
$smarty->assign('location_id',$location_id);
$smarty->assign('tube_stations',$tube_stations);
$smarty->assign('tube_station_id',$tube_station_id);
$smarty->assign('categories',$categories);
$smarty->assign('category_id',$category_id);
$smarty->assign('title',$title);
$smarty->assign('description',$description);
$smarty->assign('price',$price);
$smarty->assign('post_edit',$post_edit);
$smarty->assign('amount_ads',$amount_ads);
$smarty->assign('temp_array',$temp_array);
$smarty->assign('current_php_script',$current_php_script);



$smarty->display($current_php_script.'.tpl');

if (!is_bool($result_query)) {
mysql_free_result($result_query);
}
mysql_close($conn);

?>

