<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_saha = "localhost";
$database_saha = "charwsap_charity";
$username_saha = "charwsap_charity_user";
$password_saha = "Bhaktapur8United"; 
$saha = mysqli_connect($hostname_saha, $username_saha, $password_saha, $database_saha) or trigger_error(mysqli_error($saha),E_USER_ERROR);
 
mysqli_set_charset( $saha, 'utf8');
?>