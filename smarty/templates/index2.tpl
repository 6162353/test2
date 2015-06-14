{* комментарий *}

{assign var=time value='555'}


привет как дела, {$name}?
<br>
Текущее время: {$time}
<br>
Server name: {$smarty.server.SERVER_NAME}  {* $_SERVER['SERVER_NAME'] *}
<br>
Get: {$smarty.get.id}  {* $_GET['id'] *}

