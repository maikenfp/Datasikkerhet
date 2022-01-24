<?php 
// Innlogging til server
$db_host="158.39.188.205"; //sett server ip
$db_user="root"; //
$db_password="fCXy2uqwHE";
$db_name="datasikkerhet"; // Navn på database

try {
    $db=new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_passwird);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    $e->getMessage();
}
?>