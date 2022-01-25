<?php 
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 include_once '../../config/Database.php';
 include_once '../../models/Emne.php';

 $database = new Database();
 $db = $database->connect();

 $emne = new Emne($db);

 $emne->emnenavn = isset($_GET['emnenavn']) ? $_GET['emnenavn'] : die();

 $emne->read_single();

 $emne_arr = array(
   'emnekode' => $emne->emnekode,
   'emnenavn' => $emne->emnenavn
 );

 print_r(json_encode($emne_arr));
