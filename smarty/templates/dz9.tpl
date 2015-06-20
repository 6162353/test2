<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>avito.ru</title>
</head>
<body>
    
<!-- ФОРМА -->    
    
<form  method="post">
<div class="form-row-indented"> 
<label class="form-label-radio">
<input type="radio" {$checkedPrivate} value='1' name="private">Частное лицо
</label> 
    <label class="form-label-radio">
<input type="radio" {$checkedCompany} value='0' name="private">Компания</label> </div>


<!-- ИМЯ -->
    
<div class="form-row"> 
<label for="fld_seller_name" class="form-label">
<b id="your-name">Ваше имя</b></label>
<input type="text" maxlength="40" class="form-input-text" 
       value="{$seller_name}" name="seller_name" id="fld_seller_name">
</div>




<div class="form-row"> <label for="fld_email" class="form-label">Электронная почта</label>
    <input type="text" class="form-input-text"
           value="{$email}" name="email" id="fld_email">
</div>
    
    
    
<div class="form-row-indented"> <label class="form-label-checkbox" for="allow_mails">
<input type="checkbox" {$checked_allow_mails} value="1" name="allow_mails" id="allow_mails" 
   class="form-input-checkbox">
<span class="form-text-checkbox">Я не хочу получать вопросы по объявлению по e-mail</span>
    </label> </div>


   <!-- ТЕЛЕФОН -->
    
<div class="form-row"> <label id="fld_phone_label" 
for="fld_phone" class="form-label">Номер телефона</label> 
<input type="text" class="form-input-text" value="{$phone}" name="phone" id="fld_phone">
</div>
    



<!-- ГОРОД -->

<div id="f_location_id" class="form-row form-row-required"> 
<label for="region" class="form-label">Город</label> 
<select title="Выберите Ваш город" name="location_id" id="region" class="form-input-select"> 
<option value="">-- Выберите город --</option>
<option class="opt-group" disabled="disabled">-- Города --</option>

{foreach from=$cities key=city item=value}
    
<option 
    
       {if $location_id==$value}
            {$selected} 
    {/if}
    
    data-coords=",," 
    value="{$value}"
    >{$city}

</option>    
    
{/foreach}

</select></div>



<!-- МЕТРО -->

<div id="f_metro_id"> 
    <select title="Выберите станцию метро" name="metro_id" id="fld_metro_id" 
    class="form-input-select"> <option value="">-- Выберите станцию метро --</option>'

{foreach from=$tube_stations key=tube_station item=value2}
    
<option 
    
       {if $tube_station_id==$value2 }
            {$selected} 
    {/if}
    
    value="{$value2}"
    >{$tube_station}

</option>    
{/foreach}

</select> </div>





<!-- КАТЕГОРИЯ -->

<div class="form-row"> 
<label for="fld_category_id" class="form-label">Категория</label> 
<select title="Выберите категорию объявления" name="category_id" 
id="fld_category_id" class="form-input-select">
<option value="">-- Выберите категорию --</option>

{foreach from=$categories key=key4 item=category}
    
<optgroup label="{$key4}"> 
    
        {foreach from=$category  key=key3 item=value3}
    <option 
        
       {if $category_id==$value3}
            {$selected} 
    {/if}
        
        value="{$value3}">{$key3}</option>
        {/foreach}

</optgroup>

{/foreach}

</select> </div>





<!-- НАЗВАНИЕ ОБЪЯВЛЕНИЯ -->

<div id="f_title" class="form-row f_title"> 
<label for="fld_title" class="form-label">Название объявления</label> 
<input type="text" maxlength="50" class="form-input-text-long" 
value="{$title}" name="title" id="fld_title"> </div>






<!-- ОПИСАНИЕ ОБЪЯВЛЕНИЯ -->

<div class="form-row"> 
<label for="fld_description" class="form-label" id="js-description-label">Описание объявления</label>
<textarea maxlength="3000" name="description" 
          id="fld_description" class="form-input-textarea">{$description}</textarea> </div>

          
          
        
          
<!-- ЦЕНА ОБЪЯВЛЕНИЯ -->

<div id="price_rw" class="form-row rl"> 
<label id="price_lbl" for="fld_price" class="form-label">Цена</label> 
<input type="text" maxlength="9" class="form-input-text-short" value="{$price}" 
name="price" id="fld_price">&nbsp;<span id="fld_price_title">руб.</span> 
<a class="link_plain grey right_price c-2 icon-link" id="js-price-link" 
   href="/info/pravilnye_ceny?plain"><span>Правильно указывайте цену</span></a> 
</div>



<!-- КНОПКИ  -->
    <div class="form-row-indented form-row-submit b-vas-submit" id="js_additem_form_submit">
        <div class="vas-submit-button pull-left"> <span class="vas-submit-border"></span> 
        <span class="vas-submit-triangle"></span> 

            {if $post_edit}

<input type="submit" value="Записать изменения" id="form_submit" name="form" 
class="vas-submit-input">
<input type="submit" value="Назад" id="form_submit" name="form" class="vas-submit-input">
</div>

{else}

    <input type="submit" value="Добавить" id="form_submit" name="main_form" 
        class="vas-submit-input">
        

        {/if}
        
        </div>
    </div>
</form>


        
       
 {if $amount_ads} 
<br><br><br><b>Ваши объявления</b>
<br><p>Название объявления | Цена | Имя | Удалить</p>
    
{foreach from=$temp_array key=key5 item=value5} 
    
<p><a href={$site_dir}/{$current_php_script}.php?edit=1&id={$temp_array.$key5.id}>{$temp_array.$key5.title}</a> | 
        {$temp_array.$key5.price} | 
        {$temp_array.$key5.user_name} | 
        <a href={$site_dir}/{$current_php_script}.php?del=1&id={$temp_array.$key5.id}>Удалить</a></p>
{/foreach}
{/if}   



</body>
    </html>
    
