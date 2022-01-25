<?php 
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 include_once '../../config/Database.php';
 include_once '../../models/Teacher.php';

 $database = new Database();
 $db = $database->connect();

 $teacher = new Teacher($db);

 $teacher->navn = isset($_GET['navn']) ? $_GET['navn'] : die();

 $teacher->read_single();

 $teacher_arr = array(
   'epost' => $teacher->epost,
   'navn' => $teacher->navn
 );

 print_r(json_encode($teacher_arr));
