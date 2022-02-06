<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Teacher.php';

  $database = new Database();
  $db = $database->connect();

  $teacher = new Teacher($db);

  $data = json_decode(file_get_contents("php://input"));

  $teacher->navn = $data->navn;
  $teacher->passord = $data->passord;

  if($teacher->delete()) {
    echo json_encode(
      array('message' => 'Teacher deleted')
    );
  } else {
    echo json_encode(
      array('message' => 'Teacher not deleted')
    );
  }
