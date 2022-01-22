<?php 
// Innlogging til server
$db_host="localhost"; //sett server ip
$db_user="root"; //
$db_password="";
$db_name="php_pdo_login_db"; // Navn på database

try {
    $db=new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_passwird);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    $e->getMessage();
}
?>