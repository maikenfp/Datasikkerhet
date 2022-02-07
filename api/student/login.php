<?php 
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 include_once '../../config/Database.php';
 include_once '../../models/Student.php';

 $database = new Database();
 $db = $database->connect();

 $student = new Student($db);

 $data = json_decode(file_get_contents("php://input"));

  $student->epost = $data->epost;
  $student->passord = $data->passord;

 $student->login();

 $student_arr = array(
   'epost' => $student->epost,
   'navn' => $student->navn,
   'passord' => $student->passord,
   'studieretning' => $student->studieretning,
   'studiekull' => $student->studiekull
 );

 print_r(json_encode($student_arr));
