<?php 
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 include_once '../../config/Database.php';
 include_once '../../models/Student.php';

 $database = new Database();
 $db = $database->connect();

 $student = new Student($db);

 $student->navn = isset($_GET['navn']) ? $_GET['navn'] : die();

 $student->read_single();

 $student_arr = array(
   'epost' => $student->epost,
   'navn' => $student->navn
 );

 print_r(json_encode($student_arr));
