<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Student.php';

  $database = new Database();
  $db = $database->connect();

  $student = new Student($db);

  $data = json_decode(file_get_contents("php://input"));

  $student->navn = $data->navn;

  if($student->delete()) {
    echo json_encode(
      array('message' => 'Student deleted')
    );
  } else {
    echo json_encode(
      array('message' => 'Student not deleted')
    );
  }
