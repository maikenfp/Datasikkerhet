<?php
session_start();

$username = "";
$email = "";
$passord = "";
$errors = array();

// koble til database
include_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

// Registere bruker
if(isset($_POST["reg_user"])) {
|   $username = 
}